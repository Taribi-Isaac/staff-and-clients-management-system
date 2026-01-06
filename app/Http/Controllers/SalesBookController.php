<?php

namespace App\Http\Controllers;

use App\Models\SalesBookEntry;
use App\Models\Invoice;
use App\Models\Clients;
use Illuminate\Http\Request;

class SalesBookController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $entries = SalesBookEntry::when($query, function ($q) use ($query) {
            $q->where('client_name', 'LIKE', "%{$query}%")
              ->orWhere('invoice_number', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%");
        })
        ->when($status, function ($q) use ($status) {
            $q->where('payment_status', $status);
        })
        ->when($startDate, function ($q) use ($startDate) {
            $q->whereDate('entry_date', '>=', $startDate);
        })
        ->when($endDate, function ($q) use ($endDate) {
            $q->whereDate('entry_date', '<=', $endDate);
        })
        ->with(['creator', 'invoice', 'client'])
        ->orderBy('entry_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        $totalSales = SalesBookEntry::sum('total');
        $paidSales = SalesBookEntry::where('payment_status', 'paid')->sum('total');
        $pendingSales = SalesBookEntry::where('payment_status', 'pending')->sum('total');

        return view('finance.sales-book.index', compact('entries', 'totalSales', 'paidSales', 'pendingSales'));
    }

    public function create()
    {
        $invoices = Invoice::where('type', 'invoice')->orderBy('invoice_date', 'desc')->get();
        return view('finance.sales-book.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'invoice_id' => 'nullable|exists:invoices,id',
            'client_id' => 'nullable|exists:clients,id',
            'client_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,partial,paid',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        SalesBookEntry::create([
            'entry_date' => $request->entry_date,
            'invoice_number' => $request->invoice_number,
            'invoice_id' => $request->invoice_id,
            'client_id' => $request->client_id,
            'client_name' => $request->client_name,
            'amount' => $request->amount,
            'tax_amount' => $request->tax_amount ?? 0,
            'discount' => $request->discount ?? 0,
            'total' => $request->total,
            'payment_status' => $request->payment_status,
            'paid_amount' => $request->paid_amount ?? 0,
            'payment_method' => $request->payment_method,
            'description' => $request->description,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('sales-book.index')
            ->with('success', 'Sales book entry created successfully!');
    }

    public function show(string $id)
    {
        $entry = SalesBookEntry::with(['creator', 'invoice', 'client'])->findOrFail($id);
        return view('finance.sales-book.show', compact('entry'));
    }

    public function edit(string $id)
    {
        $entry = SalesBookEntry::findOrFail($id);
        $invoices = Invoice::where('type', 'invoice')->orderBy('invoice_date', 'desc')->get();
        return view('finance.sales-book.edit', compact('entry', 'invoices'));
    }

    public function update(Request $request, string $id)
    {
        $entry = SalesBookEntry::findOrFail($id);

        $request->validate([
            'entry_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'invoice_id' => 'nullable|exists:invoices,id',
            'client_id' => 'nullable|exists:clients,id',
            'client_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,partial,paid',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $entry->update($request->all());

        return redirect()->route('sales-book.index')
            ->with('success', 'Sales book entry updated successfully!');
    }

    public function destroy(string $id)
    {
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized action.');
        }

        $entry = SalesBookEntry::findOrFail($id);
        $entry->delete();

        return redirect()->route('sales-book.index')
            ->with('success', 'Sales book entry deleted successfully!');
    }

    public function export(Request $request)
    {
        $query = $request->input('search');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $entries = SalesBookEntry::when($query, function ($q) use ($query) {
            $q->where('client_name', 'LIKE', "%{$query}%")
              ->orWhere('invoice_number', 'LIKE', "%{$query}%");
        })
        ->when($status, function ($q) use ($status) {
            $q->where('payment_status', $status);
        })
        ->when($startDate, function ($q) use ($startDate) {
            $q->whereDate('entry_date', '>=', $startDate);
        })
        ->when($endDate, function ($q) use ($endDate) {
            $q->whereDate('entry_date', '<=', $endDate);
        })
        ->with(['creator', 'invoice', 'client'])
        ->orderBy('entry_date', 'desc')
        ->get();

        $exportData = $entries->map(function ($entry) {
            return [
                'Date' => $entry->entry_date->format('Y-m-d'),
                'Client Name' => $entry->client_name,
                'Invoice Number' => $entry->invoice_number ?? 'N/A',
                'Amount' => number_format($entry->amount, 2),
                'Tax Amount' => number_format($entry->tax_amount, 2),
                'Discount' => number_format($entry->discount, 2),
                'Total' => number_format($entry->total, 2),
                'Payment Status' => ucfirst($entry->payment_status),
                'Paid Amount' => number_format($entry->paid_amount, 2),
                'Payment Method' => $entry->payment_method ?? 'N/A',
                'Description' => $entry->description ?? '',
                'Created By' => $entry->creator->name ?? 'N/A',
            ];
        });

        $filename = 'sales_book_' . date('Y-m-d_His') . '.csv';
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
