<?php

namespace App\Http\Controllers;

use App\Models\ArLedgerEntry;
use App\Models\Clients;
use App\Models\Invoice;
use Illuminate\Http\Request;

class ArLedgerController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $entries = ArLedgerEntry::when($query, function ($q) use ($query) {
            $q->where('client_name', 'LIKE', "%{$query}%")
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
        ->with(['creator', 'client', 'invoice'])
        ->orderBy('entry_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        $totalReceivable = ArLedgerEntry::sum('amount');
        $paidAmount = ArLedgerEntry::sum('paid_amount');
        $outstandingBalance = ArLedgerEntry::sum('balance');
        $overdueAmount = ArLedgerEntry::where('status', 'overdue')->sum('balance');

        return view('finance.ar-ledger.index', compact('entries', 'totalReceivable', 'paidAmount', 'outstandingBalance', 'overdueAmount'));
    }

    public function create()
    {
        $clients = Clients::orderBy('client_name')->get();
        $invoices = Invoice::where('type', 'invoice')->orderBy('invoice_date', 'desc')->get();
        return view('finance.ar-ledger.create', compact('clients', 'invoices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'client_id' => 'nullable|exists:clients,id',
            'client_name' => 'required|string|max:255',
            'invoice_id' => 'nullable|exists:invoices,id',
            'invoice_number' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date|after_or_equal:entry_date',
            'status' => 'required|in:pending,partial,paid,overdue,written_off',
            'paid_amount' => 'nullable|numeric|min:0',
            'balance' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        ArLedgerEntry::create([
            'entry_date' => $request->entry_date,
            'client_id' => $request->client_id,
            'client_name' => $request->client_name,
            'invoice_id' => $request->invoice_id,
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

        return redirect()->route('ar-ledger.index')
            ->with('success', 'AR Ledger entry created successfully!');
    }

    public function show(string $id)
    {
        $entry = ArLedgerEntry::with(['creator', 'client', 'invoice'])->findOrFail($id);
        return view('finance.ar-ledger.show', compact('entry'));
    }

    public function edit(string $id)
    {
        $entry = ArLedgerEntry::findOrFail($id);
        $invoices = Invoice::where('type', 'invoice')->orderBy('invoice_date', 'desc')->get();
        return view('finance.ar-ledger.edit', compact('entry', 'invoices'));
    }

    public function update(Request $request, string $id)
    {
        $entry = ArLedgerEntry::findOrFail($id);

        $request->validate([
            'entry_date' => 'required|date',
            'client_id' => 'nullable|exists:clients,id',
            'client_name' => 'required|string|max:255',
            'invoice_id' => 'nullable|exists:invoices,id',
            'invoice_number' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date|after_or_equal:entry_date',
            'status' => 'required|in:pending,partial,paid,overdue,written_off',
            'paid_amount' => 'nullable|numeric|min:0',
            'balance' => 'required|numeric|min:0',
            'last_payment_date' => 'nullable|date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $entry->update($request->all());

        return redirect()->route('ar-ledger.index')
            ->with('success', 'AR Ledger entry updated successfully!');
    }

    public function destroy(string $id)
    {
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized action.');
        }

        $entry = ArLedgerEntry::findOrFail($id);
        $entry->delete();

        return redirect()->route('ar-ledger.index')
            ->with('success', 'AR Ledger entry deleted successfully!');
    }

    public function export(Request $request)
    {
        $query = $request->input('search');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $entries = ArLedgerEntry::when($query, function ($q) use ($query) {
            $q->where('client_name', 'LIKE', "%{$query}%")
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
        ->with(['creator', 'client', 'invoice'])
        ->orderBy('entry_date', 'desc')
        ->get();

        $exportData = $entries->map(function ($entry) {
            return [
                'Date' => $entry->entry_date->format('Y-m-d'),
                'Client Name' => $entry->client_name,
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

        $filename = 'ar_ledger_' . date('Y-m-d_His') . '.csv';
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
