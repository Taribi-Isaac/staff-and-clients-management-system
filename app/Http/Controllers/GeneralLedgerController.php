<?php

namespace App\Http\Controllers;

use App\Models\CashBookEntry;
use App\Models\PettyCashEntry;
use App\Models\SalesBookEntry;
use App\Models\PurchasesBookEntry;
use App\Models\ArLedgerEntry;
use App\Models\ApLedgerEntry;
use App\Models\PayrollBookEntry;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralLedgerController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $bookType = $request->input('book_type', 'all');
        $transactionType = $request->input('transaction_type', 'all');

        // Aggregate all financial transactions
        $transactions = collect();

        // Cash Book Entries
        if ($bookType === 'all' || $bookType === 'cash_book') {
            $cashBookEntries = CashBookEntry::whereBetween('entry_date', [$startDate, $endDate])
                ->when($transactionType !== 'all', function ($q) use ($transactionType) {
                    if ($transactionType === 'receipt') {
                        $q->where('transaction_type', 'receipt');
                    } elseif ($transactionType === 'payment') {
                        $q->where('transaction_type', 'payment');
                    }
                })
                ->get()
                ->map(function ($entry) {
                    return [
                        'date' => $entry->entry_date,
                        'book' => 'Cash Book',
                        'reference' => $entry->reference ?? 'N/A',
                        'description' => $entry->description,
                        'debit' => $entry->transaction_type === 'receipt' ? $entry->amount : 0,
                        'credit' => $entry->transaction_type === 'payment' ? $entry->amount : 0,
                        'balance' => $entry->balance,
                        'type' => $entry->transaction_type,
                        'entity' => $entry->client?->client_name ?? 'N/A',
                    ];
                });
            $transactions = $transactions->merge($cashBookEntries);
        }

        // Petty Cash Entries (only authorized)
        if ($bookType === 'all' || $bookType === 'petty_cash') {
            $pettyCashEntries = PettyCashEntry::whereBetween('entry_date', [$startDate, $endDate])
                ->where('authorization_status', 'authorized') // Only count authorized entries
                ->when($transactionType !== 'all', function ($q) use ($transactionType) {
                    if ($transactionType === 'expense') {
                        $q->where('transaction_type', 'expense');
                    } elseif ($transactionType === 'replenishment') {
                        $q->where('transaction_type', 'replenishment');
                    }
                })
                ->get()
                ->map(function ($entry) {
                    return [
                        'date' => $entry->entry_date,
                        'book' => 'Petty Cash',
                        'reference' => $entry->receipt_number ?? 'N/A',
                        'description' => $entry->description,
                        'debit' => $entry->transaction_type === 'replenishment' ? $entry->amount : 0,
                        'credit' => $entry->transaction_type === 'expense' ? $entry->amount : 0,
                        'balance' => 0,
                        'type' => $entry->transaction_type,
                        'entity' => $entry->receiver_beneficiary ?? 'N/A',
                    ];
                });
            $transactions = $transactions->merge($pettyCashEntries);
        }

        // Sales Book Entries
        if ($bookType === 'all' || $bookType === 'sales_book') {
            $salesBookEntries = SalesBookEntry::whereBetween('entry_date', [$startDate, $endDate])
                ->get()
                ->map(function ($entry) {
                    return [
                        'date' => $entry->entry_date,
                        'book' => 'Sales Book',
                        'reference' => $entry->invoice_number ?? 'N/A',
                        'description' => $entry->description ?? 'Sales',
                        'debit' => $entry->total,
                        'credit' => 0,
                        'balance' => 0,
                        'type' => 'sale',
                        'entity' => $entry->client_name,
                    ];
                });
            $transactions = $transactions->merge($salesBookEntries);
        }

        // Purchases Book Entries
        if ($bookType === 'all' || $bookType === 'purchases_book') {
            $purchasesBookEntries = PurchasesBookEntry::whereBetween('entry_date', [$startDate, $endDate])
                ->get()
                ->map(function ($entry) {
                    return [
                        'date' => $entry->entry_date,
                        'book' => 'Purchases Book',
                        'reference' => $entry->invoice_number ?? 'N/A',
                        'description' => $entry->description ?? 'Purchase',
                        'debit' => 0,
                        'credit' => $entry->total,
                        'balance' => 0,
                        'type' => 'purchase',
                        'entity' => $entry->supplier_name,
                    ];
                });
            $transactions = $transactions->merge($purchasesBookEntries);
        }

        // AR Ledger Entries
        if ($bookType === 'all' || $bookType === 'ar_ledger') {
            $arLedgerEntries = ArLedgerEntry::whereBetween('entry_date', [$startDate, $endDate])
                ->get()
                ->map(function ($entry) {
                    return [
                        'date' => $entry->entry_date,
                        'book' => 'AR Ledger',
                        'reference' => $entry->invoice_number ?? 'N/A',
                        'description' => $entry->description ?? 'Accounts Receivable',
                        'debit' => $entry->amount,
                        'credit' => $entry->paid_amount,
                        'balance' => $entry->balance,
                        'type' => $entry->status,
                        'entity' => $entry->client_name,
                    ];
                });
            $transactions = $transactions->merge($arLedgerEntries);
        }

        // AP Ledger Entries
        if ($bookType === 'all' || $bookType === 'ap_ledger') {
            $apLedgerEntries = ApLedgerEntry::whereBetween('entry_date', [$startDate, $endDate])
                ->get()
                ->map(function ($entry) {
                    return [
                        'date' => $entry->entry_date,
                        'book' => 'AP Ledger',
                        'reference' => $entry->invoice_number ?? 'N/A',
                        'description' => $entry->description ?? 'Accounts Payable',
                        'debit' => $entry->paid_amount,
                        'credit' => $entry->amount,
                        'balance' => $entry->balance,
                        'type' => $entry->status,
                        'entity' => $entry->supplier_name,
                    ];
                });
            $transactions = $transactions->merge($apLedgerEntries);
        }

        // Payroll Book Entries
        if ($bookType === 'all' || $bookType === 'payroll_book') {
            $payrollBookEntries = PayrollBookEntry::whereBetween('entry_date', [$startDate, $endDate])
                ->get()
                ->map(function ($entry) {
                    return [
                        'date' => $entry->entry_date,
                        'book' => 'Payroll Book',
                        'reference' => 'PAY-' . $entry->id,
                        'description' => $entry->description ?? 'Payroll Payment',
                        'debit' => 0,
                        'credit' => $entry->net_pay,
                        'balance' => 0,
                        'type' => $entry->payment_status,
                        'entity' => $entry->employee_name,
                    ];
                });
            $transactions = $transactions->merge($payrollBookEntries);
        }

        // Sort by date
        $transactions = $transactions->sortBy('date')->values();

        // Calculate running balance
        $runningBalance = 0;
        $transactions = $transactions->map(function ($transaction) use (&$runningBalance) {
            $runningBalance += $transaction['debit'] - $transaction['credit'];
            $transaction['running_balance'] = $runningBalance;
            return $transaction;
        });

        // Calculate summary statistics
        $totalDebits = $transactions->sum('debit');
        $totalCredits = $transactions->sum('credit');
        $netAmount = $totalDebits - $totalCredits;

        // Group by book for summary
        $summaryByBook = $transactions->groupBy('book')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total_debits' => $group->sum('debit'),
                'total_credits' => $group->sum('credit'),
                'net' => $group->sum('debit') - $group->sum('credit'),
            ];
        });

        return view('finance.general-ledger.index', compact(
            'transactions',
            'totalDebits',
            'totalCredits',
            'netAmount',
            'summaryByBook',
            'startDate',
            'endDate',
            'bookType',
            'transactionType'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $bookType = $request->input('book_type', 'all');
        $transactionType = $request->input('transaction_type', 'all');

        // Get transactions using same logic as index
        $transactions = collect();

        // Cash Book Entries
        if ($bookType === 'all' || $bookType === 'cash_book') {
            $cashBookEntries = CashBookEntry::whereBetween('entry_date', [$startDate, $endDate])
                ->when($transactionType !== 'all', function ($q) use ($transactionType) {
                    if ($transactionType === 'receipt') {
                        $q->where('transaction_type', 'receipt');
                    } elseif ($transactionType === 'payment') {
                        $q->where('transaction_type', 'payment');
                    }
                })
                ->get()
                ->map(function ($entry) {
                    return [
                        'Date' => $entry->entry_date->format('Y-m-d'),
                        'Book' => 'Cash Book',
                        'Reference' => $entry->reference ?? 'N/A',
                        'Description' => $entry->description,
                        'Debit' => $entry->transaction_type === 'receipt' ? number_format($entry->amount, 2) : '0.00',
                        'Credit' => $entry->transaction_type === 'payment' ? number_format($entry->amount, 2) : '0.00',
                        'Balance' => number_format($entry->balance, 2),
                        'Entity' => $entry->client?->client_name ?? 'N/A',
                    ];
                });
            $transactions = $transactions->merge($cashBookEntries);
        }

        // Petty Cash Entries (only authorized)
        if ($bookType === 'all' || $bookType === 'petty_cash') {
            $pettyCashEntries = PettyCashEntry::whereBetween('entry_date', [$startDate, $endDate])
                ->where('authorization_status', 'authorized') // Only count authorized entries
                ->when($transactionType !== 'all', function ($q) use ($transactionType) {
                    if ($transactionType === 'expense') {
                        $q->where('transaction_type', 'expense');
                    } elseif ($transactionType === 'replenishment') {
                        $q->where('transaction_type', 'replenishment');
                    }
                })
                ->get()
                ->map(function ($entry) {
                    return [
                        'Date' => $entry->entry_date->format('Y-m-d'),
                        'Book' => 'Petty Cash',
                        'Reference' => $entry->receipt_number ?? 'N/A',
                        'Description' => $entry->description,
                        'Debit' => $entry->transaction_type === 'replenishment' ? number_format($entry->amount, 2) : '0.00',
                        'Credit' => $entry->transaction_type === 'expense' ? number_format($entry->amount, 2) : '0.00',
                        'Balance' => '0.00',
                        'Entity' => $entry->receiver_beneficiary ?? 'N/A',
                    ];
                });
            $transactions = $transactions->merge($pettyCashEntries);
        }

        // Sales Book Entries
        if ($bookType === 'all' || $bookType === 'sales_book') {
            $salesBookEntries = SalesBookEntry::whereBetween('entry_date', [$startDate, $endDate])
                ->get()
                ->map(function ($entry) {
                    return [
                        'Date' => $entry->entry_date->format('Y-m-d'),
                        'Book' => 'Sales Book',
                        'Reference' => $entry->invoice_number ?? 'N/A',
                        'Description' => $entry->description ?? 'Sales',
                        'Debit' => number_format($entry->total, 2),
                        'Credit' => '0.00',
                        'Balance' => '0.00',
                        'Entity' => $entry->client_name,
                    ];
                });
            $transactions = $transactions->merge($salesBookEntries);
        }

        // Purchases Book Entries
        if ($bookType === 'all' || $bookType === 'purchases_book') {
            $purchasesBookEntries = PurchasesBookEntry::whereBetween('entry_date', [$startDate, $endDate])
                ->get()
                ->map(function ($entry) {
                    return [
                        'Date' => $entry->entry_date->format('Y-m-d'),
                        'Book' => 'Purchases Book',
                        'Reference' => $entry->invoice_number ?? 'N/A',
                        'Description' => $entry->description ?? 'Purchase',
                        'Debit' => '0.00',
                        'Credit' => number_format($entry->total, 2),
                        'Balance' => '0.00',
                        'Entity' => $entry->supplier_name,
                    ];
                });
            $transactions = $transactions->merge($purchasesBookEntries);
        }

        // AR Ledger Entries
        if ($bookType === 'all' || $bookType === 'ar_ledger') {
            $arLedgerEntries = ArLedgerEntry::whereBetween('entry_date', [$startDate, $endDate])
                ->get()
                ->map(function ($entry) {
                    return [
                        'Date' => $entry->entry_date->format('Y-m-d'),
                        'Book' => 'AR Ledger',
                        'Reference' => $entry->invoice_number ?? 'N/A',
                        'Description' => $entry->description ?? 'Accounts Receivable',
                        'Debit' => number_format($entry->amount, 2),
                        'Credit' => number_format($entry->paid_amount, 2),
                        'Balance' => number_format($entry->balance, 2),
                        'Entity' => $entry->client_name,
                    ];
                });
            $transactions = $transactions->merge($arLedgerEntries);
        }

        // AP Ledger Entries
        if ($bookType === 'all' || $bookType === 'ap_ledger') {
            $apLedgerEntries = ApLedgerEntry::whereBetween('entry_date', [$startDate, $endDate])
                ->get()
                ->map(function ($entry) {
                    return [
                        'Date' => $entry->entry_date->format('Y-m-d'),
                        'Book' => 'AP Ledger',
                        'Reference' => $entry->invoice_number ?? 'N/A',
                        'Description' => $entry->description ?? 'Accounts Payable',
                        'Debit' => number_format($entry->paid_amount, 2),
                        'Credit' => number_format($entry->amount, 2),
                        'Balance' => number_format($entry->balance, 2),
                        'Entity' => $entry->supplier_name,
                    ];
                });
            $transactions = $transactions->merge($apLedgerEntries);
        }

        // Payroll Book Entries
        if ($bookType === 'all' || $bookType === 'payroll_book') {
            $payrollBookEntries = PayrollBookEntry::whereBetween('entry_date', [$startDate, $endDate])
                ->get()
                ->map(function ($entry) {
                    return [
                        'Date' => $entry->entry_date->format('Y-m-d'),
                        'Book' => 'Payroll Book',
                        'Reference' => 'PAY-' . $entry->id,
                        'Description' => $entry->description ?? 'Payroll Payment',
                        'Debit' => '0.00',
                        'Credit' => number_format($entry->net_pay, 2),
                        'Balance' => '0.00',
                        'Entity' => $entry->employee_name,
                    ];
                });
            $transactions = $transactions->merge($payrollBookEntries);
        }

        $transactions = $transactions->sortBy('date')->values();

        $filename = 'general_ledger_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            if ($transactions->count() > 0) {
                fputcsv($file, ['Date', 'Book', 'Reference', 'Description', 'Debit', 'Credit', 'Balance', 'Entity']);
                foreach ($transactions as $transaction) {
                    fputcsv($file, [
                        $transaction['Date'],
                        $transaction['Book'],
                        $transaction['Reference'],
                        $transaction['Description'],
                        $transaction['Debit'],
                        $transaction['Credit'],
                        $transaction['Balance'],
                        $transaction['Entity'],
                    ]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
