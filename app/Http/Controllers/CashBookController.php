<?php

namespace App\Http\Controllers;

use App\Models\CashBookEntry;
use App\Models\Invoice;
use App\Models\Clients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashBookController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $entries = CashBookEntry::when($query, function ($q) use ($query) {
            $q->where('description', 'LIKE', "%{$query}%")
              ->orWhere('reference', 'LIKE', "%{$query}%");
        })
        ->when($type, function ($q) use ($type) {
            $q->where('transaction_type', $type);
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

        // Calculate totals
        $totalReceipts = CashBookEntry::where('transaction_type', 'receipt')->sum('amount');
        $totalPayments = CashBookEntry::where('transaction_type', 'payment')->sum('amount');
        $currentBalance = $totalReceipts - $totalPayments;

        return view('finance.cash-book.index', compact('entries', 'totalReceipts', 'totalPayments', 'currentBalance'));
    }

    public function create()
    {
        $invoices = Invoice::orderBy('invoice_date', 'desc')->get();
        $clients = Clients::orderBy('client_name')->get();
        return view('finance.cash-book.create', compact('invoices', 'clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'transaction_type' => 'required|in:receipt,payment',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
            'related_invoice_id' => 'nullable|exists:invoices,id',
            'related_client_id' => 'nullable|exists:clients,id',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Calculate running balance
            $lastEntry = CashBookEntry::orderBy('entry_date', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            
            $lastBalance = $lastEntry ? $lastEntry->balance : 0;
            $newBalance = $request->transaction_type === 'receipt' 
                ? $lastBalance + $request->amount 
                : $lastBalance - $request->amount;

            $entry = CashBookEntry::create([
                'entry_date' => $request->entry_date,
                'transaction_type' => $request->transaction_type,
                'amount' => $request->amount,
                'description' => $request->description,
                'reference' => $request->reference,
                'related_invoice_id' => $request->related_invoice_id,
                'related_client_id' => $request->related_client_id,
                'balance' => $newBalance,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('cash-book.index')
                ->with('success', 'Cash book entry created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create entry: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $entry = CashBookEntry::with(['creator', 'invoice', 'client'])->findOrFail($id);
        return view('finance.cash-book.show', compact('entry'));
    }

    public function edit(string $id)
    {
        $entry = CashBookEntry::findOrFail($id);
        $invoices = Invoice::orderBy('invoice_date', 'desc')->get();
        $clients = Clients::orderBy('client_name')->get();
        return view('finance.cash-book.edit', compact('entry', 'invoices', 'clients'));
    }

    public function update(Request $request, string $id)
    {
        $entry = CashBookEntry::findOrFail($id);

        $request->validate([
            'entry_date' => 'required|date',
            'transaction_type' => 'required|in:receipt,payment',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
            'related_invoice_id' => 'nullable|exists:invoices,id',
            'related_client_id' => 'nullable|exists:clients,id',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Recalculate balance for all entries after this one
            $entry->update($request->only([
                'entry_date', 'transaction_type', 'amount', 'description', 
                'reference', 'related_invoice_id', 'related_client_id', 'notes'
            ]));

            // Recalculate balances
            $this->recalculateBalances($entry->entry_date);

            DB::commit();

            return redirect()->route('cash-book.index')
                ->with('success', 'Cash book entry updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update entry: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            $entry = CashBookEntry::findOrFail($id);
            $entryDate = $entry->entry_date;
            $entry->delete();

            // Recalculate balances
            $this->recalculateBalances($entryDate);

            DB::commit();

            return redirect()->route('cash-book.index')
                ->with('success', 'Cash book entry deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete entry: ' . $e->getMessage());
        }
    }

    private function recalculateBalances($fromDate = null)
    {
        $query = CashBookEntry::orderBy('entry_date', 'asc')->orderBy('id', 'asc');
        
        if ($fromDate) {
            $query->whereDate('entry_date', '>=', $fromDate);
        }

        $entries = $query->get();
        $runningBalance = 0;

        // Get balance before the date if recalculating from a specific date
        if ($fromDate) {
            $lastEntryBefore = CashBookEntry::whereDate('entry_date', '<', $fromDate)
                ->orderBy('entry_date', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            $runningBalance = $lastEntryBefore ? $lastEntryBefore->balance : 0;
        }

        foreach ($entries as $entry) {
            if ($entry->transaction_type === 'receipt') {
                $runningBalance += $entry->amount;
            } else {
                $runningBalance -= $entry->amount;
            }
            $entry->balance = $runningBalance;
            $entry->save();
        }
    }

    public function export(Request $request)
    {
        $query = $request->input('search');
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $entries = CashBookEntry::when($query, function ($q) use ($query) {
            $q->where('description', 'LIKE', "%{$query}%")
              ->orWhere('reference', 'LIKE', "%{$query}%");
        })
        ->when($type, function ($q) use ($type) {
            $q->where('transaction_type', $type);
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
        ->get();

        $exportData = $entries->map(function ($entry) {
            return [
                'Date' => $entry->entry_date->format('Y-m-d'),
                'Type' => ucfirst($entry->transaction_type),
                'Amount' => number_format($entry->amount, 2),
                'Description' => $entry->description,
                'Reference' => $entry->reference ?? 'N/A',
                'Client' => $entry->client?->client_name ?? 'N/A',
                'Invoice' => $entry->invoice?->invoice_number ?? 'N/A',
                'Balance' => number_format($entry->balance, 2),
                'Notes' => $entry->notes ?? '',
                'Created By' => $entry->creator->name ?? 'N/A',
                'Created At' => $entry->created_at->format('Y-m-d H:i:s'),
            ];
        });

        $filename = 'cash_book_' . date('Y-m-d_His') . '.csv';
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
