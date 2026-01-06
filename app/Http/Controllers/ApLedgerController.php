<?php

namespace App\Http\Controllers;

use App\Models\ApLedgerEntry;
use App\Models\Supplier;
use App\Models\PurchasesBookEntry;
use Illuminate\Http\Request;

class ApLedgerController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $entries = ApLedgerEntry::when($query, function ($q) use ($query) {
            $q->where('supplier_name', 'LIKE', "%{$query}%")
              ->orWhere('invoice_number', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%");
        })
        ->when($status, function ($q) use ($status) {
            $q->where('status', $status);
        })
        ->when($startDate, function ($q) use ($startDate) {
            $q->whereDate('entry_date', '>=', $startDate);
        })
        ->when($endDate, function ($q) use ($endDate) {
            $q->whereDate('entry_date', '<=', $endDate);
        })
        ->with(['creator', 'supplier', 'purchaseEntry'])
        ->orderBy('entry_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        $totalPayable = ApLedgerEntry::sum('amount');
        $paidAmount = ApLedgerEntry::sum('paid_amount');
        $outstandingBalance = ApLedgerEntry::sum('balance');
        $overdueAmount = ApLedgerEntry::where('status', 'overdue')->sum('balance');

        return view('finance.ap-ledger.index', compact('entries', 'totalPayable', 'paidAmount', 'outstandingBalance', 'overdueAmount'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('supplier_name')->get();
        $purchases = PurchasesBookEntry::orderBy('entry_date', 'desc')->get();
        return view('finance.ap-ledger.create', compact('suppliers', 'purchases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'supplier_name' => 'required|string|max:255',
            'purchase_entry_id' => 'nullable|exists:purchases_book_entries,id',
            'invoice_number' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date|after_or_equal:entry_date',
            'status' => 'required|in:pending,partial,paid,overdue',
            'paid_amount' => 'nullable|numeric|min:0',
            'balance' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        ApLedgerEntry::create([
            'entry_date' => $request->entry_date,
            'supplier_id' => $request->supplier_id,
            'supplier_name' => $request->supplier_name,
            'purchase_entry_id' => $request->purchase_entry_id,
            'invoice_number' => $request->invoice_number,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'paid_amount' => $request->paid_amount ?? 0,
            'balance' => $request->balance,
            'description' => $request->description,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('ap-ledger.index')
            ->with('success', 'AP Ledger entry created successfully!');
    }

    public function show(string $id)
    {
        $entry = ApLedgerEntry::with(['creator', 'supplier', 'purchaseEntry'])->findOrFail($id);
        return view('finance.ap-ledger.show', compact('entry'));
    }

    public function edit(string $id)
    {
        $entry = ApLedgerEntry::findOrFail($id);
        $suppliers = Supplier::orderBy('supplier_name')->get();
        $purchases = PurchasesBookEntry::orderBy('entry_date', 'desc')->get();
        return view('finance.ap-ledger.edit', compact('entry', 'suppliers', 'purchases'));
    }

    public function update(Request $request, string $id)
    {
        $entry = ApLedgerEntry::findOrFail($id);

        $request->validate([
            'entry_date' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'supplier_name' => 'required|string|max:255',
            'purchase_entry_id' => 'nullable|exists:purchases_book_entries,id',
            'invoice_number' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date|after_or_equal:entry_date',
            'status' => 'required|in:pending,partial,paid,overdue',
            'paid_amount' => 'nullable|numeric|min:0',
            'balance' => 'required|numeric|min:0',
            'last_payment_date' => 'nullable|date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $entry->update($request->all());

        return redirect()->route('ap-ledger.index')
            ->with('success', 'AP Ledger entry updated successfully!');
    }

    public function destroy(string $id)
    {
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized action.');
        }

        $entry = ApLedgerEntry::findOrFail($id);
        $entry->delete();

        return redirect()->route('ap-ledger.index')
            ->with('success', 'AP Ledger entry deleted successfully!');
    }

    public function export(Request $request)
    {
        $query = $request->input('search');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $entries = ApLedgerEntry::when($query, function ($q) use ($query) {
            $q->where('supplier_name', 'LIKE', "%{$query}%")
              ->orWhere('invoice_number', 'LIKE', "%{$query}%");
        })
        ->when($status, function ($q) use ($status) {
            $q->where('status', $status);
        })
        ->when($startDate, function ($q) use ($startDate) {
            $q->whereDate('entry_date', '>=', $startDate);
        })
        ->when($endDate, function ($q) use ($endDate) {
            $q->whereDate('entry_date', '<=', $endDate);
        })
        ->with(['creator', 'supplier', 'purchaseEntry'])
        ->orderBy('entry_date', 'desc')
        ->get();

        $exportData = $entries->map(function ($entry) {
            return [
                'Date' => $entry->entry_date->format('Y-m-d'),
                'Supplier Name' => $entry->supplier_name,
                'Invoice Number' => $entry->invoice_number ?? 'N/A',
                'Due Date' => $entry->due_date->format('Y-m-d'),
                'Amount' => number_format($entry->amount, 2),
                'Paid Amount' => number_format($entry->paid_amount, 2),
                'Balance' => number_format($entry->balance, 2),
                'Status' => ucfirst($entry->status),
                'Description' => $entry->description ?? '',
                'Created By' => $entry->creator->name ?? 'N/A',
            ];
        });

        $filename = 'ap_ledger_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($exportData) {
            $file = fopen('php://output', 'w');
            if ($exportData->count() > 0) {
                fputcsv($file, array_keys($exportData->first()));
                foreach ($exportData as $row) {
                    fputcsv($file, $row);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
