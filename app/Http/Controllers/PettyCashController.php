<?php

namespace App\Http\Controllers;

use App\Models\PettyCashEntry;
use App\Models\User;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PettyCashController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $type = $request->input('type');
        $category = $request->input('category');
        $authorizationStatus = $request->input('authorization_status');
        $employeeFilter = $request->input('employee_filter'); // 'all', 'with_employee', 'without_employee', or specific employee_id
        $employeeId = $request->input('employee_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $entries = PettyCashEntry::when($query, function ($q) use ($query) {
            $q->where('description', 'LIKE', "%{$query}%")
              ->orWhere('receipt_number', 'LIKE', "%{$query}%");
        })
        ->when($type, function ($q) use ($type) {
            $q->where('transaction_type', $type);
        })
        ->when($category, function ($q) use ($category) {
            $q->where('category', $category);
        })
        ->when($authorizationStatus, function ($q) use ($authorizationStatus) {
            $q->where('authorization_status', $authorizationStatus);
        })
        ->when($employeeFilter === 'with_employee', function ($q) {
            $q->whereNotNull('employee_id');
        })
        ->when($employeeFilter === 'without_employee', function ($q) {
            $q->whereNull('employee_id');
        })
        ->when($employeeId, function ($q) use ($employeeId) {
            $q->where('employee_id', $employeeId);
        })
        ->when($startDate, function ($q) use ($startDate) {
            $q->whereDate('entry_date', '>=', $startDate);
        })
        ->when($endDate, function ($q) use ($endDate) {
            $q->whereDate('entry_date', '<=', $endDate);
        })
        ->with(['creator', 'approver', 'authorizer', 'employee'])
        ->orderBy('entry_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        // Only count authorized transactions
        $totalExpenses = PettyCashEntry::where('transaction_type', 'expense')
            ->where('authorization_status', 'authorized')
            ->sum('amount');
        $totalReplenishments = PettyCashEntry::where('transaction_type', 'replenishment')
            ->where('authorization_status', 'authorized')
            ->sum('amount');
        $currentBalance = $totalReplenishments - $totalExpenses;

        // Get counts for statistics
        $pendingCount = PettyCashEntry::where('authorization_status', 'pending')->count();
        $authorizedCount = PettyCashEntry::where('authorization_status', 'authorized')->count();
        $unauthorizedCount = PettyCashEntry::where('authorization_status', 'unauthorized')->count();

        // Get employees for filter dropdown
        $employees = Employees::where('status', 'active')->orderBy('name')->get();
        
        return view('finance.petty-cash.index', compact('entries', 'totalExpenses', 'totalReplenishments', 'currentBalance', 'pendingCount', 'authorizedCount', 'unauthorizedCount', 'employees'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $employees = Employees::where('status', 'active')->orderBy('name')->get();
        return view('finance.petty-cash.create', compact('users', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'transaction_type' => 'required|in:expense,replenishment',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'receiver_beneficiary' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'receipt_number' => 'nullable|string|max:255',
            'approved_by' => 'nullable|string|max:255',
            'approved_by_user_id' => 'nullable|exists:users,id',
            'employee_id' => 'nullable|exists:employees,id',
            'notes' => 'nullable|string',
        ]);

        PettyCashEntry::create([
            'entry_date' => $request->entry_date,
            'transaction_type' => $request->transaction_type,
            'amount' => $request->amount,
            'description' => $request->description,
            'receiver_beneficiary' => $request->receiver_beneficiary,
            'category' => $request->category,
            'receipt_number' => $request->receipt_number,
            'approved_by' => $request->approved_by,
            'approved_by_user_id' => $request->approved_by_user_id,
            'employee_id' => $request->employee_id,
            'authorization_status' => 'pending', // Default to pending
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('petty-cash.index')
            ->with('success', 'Petty cash entry created successfully!');
    }

    public function show(string $id)
    {
        $entry = PettyCashEntry::with(['creator', 'approver'])->findOrFail($id);
        return view('finance.petty-cash.show', compact('entry'));
    }

    public function edit(string $id)
    {
        $entry = PettyCashEntry::findOrFail($id);
        $users = User::orderBy('name')->get();
        $employees = Employees::where('status', 'active')->orderBy('name')->get();
        return view('finance.petty-cash.edit', compact('entry', 'users', 'employees'));
    }

    public function update(Request $request, string $id)
    {
        $entry = PettyCashEntry::findOrFail($id);

        $request->validate([
            'entry_date' => 'required|date',
            'transaction_type' => 'required|in:expense,replenishment',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'receiver_beneficiary' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'receipt_number' => 'nullable|string|max:255',
            'approved_by' => 'nullable|string|max:255',
            'approved_by_user_id' => 'nullable|exists:users,id',
            'employee_id' => 'nullable|exists:employees,id',
            'notes' => 'nullable|string',
        ]);

        $entry->update($request->all());

        return redirect()->route('petty-cash.index')
            ->with('success', 'Petty cash entry updated successfully!');
    }

    public function destroy(string $id)
    {
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized action.');
        }

        $entry = PettyCashEntry::findOrFail($id);
        $entry->delete();

        return redirect()->route('petty-cash.index')
            ->with('success', 'Petty cash entry deleted successfully!');
    }

    public function export(Request $request)
    {
        $query = $request->input('search');
        $type = $request->input('type');
        $employeeFilter = $request->input('employee_filter');
        $employeeId = $request->input('employee_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $entries = PettyCashEntry::when($query, function ($q) use ($query) {
            $q->where('description', 'LIKE', "%{$query}%")
              ->orWhere('receipt_number', 'LIKE', "%{$query}%");
        })
        ->when($type, function ($q) use ($type) {
            $q->where('transaction_type', $type);
        })
        ->when($employeeFilter === 'with_employee', function ($q) {
            $q->whereNotNull('employee_id');
        })
        ->when($employeeFilter === 'without_employee', function ($q) {
            $q->whereNull('employee_id');
        })
        ->when($employeeId, function ($q) use ($employeeId) {
            $q->where('employee_id', $employeeId);
        })
        ->when($startDate, function ($q) use ($startDate) {
            $q->whereDate('entry_date', '>=', $startDate);
        })
        ->when($endDate, function ($q) use ($endDate) {
            $q->whereDate('entry_date', '<=', $endDate);
        })
        ->with(['creator', 'approver', 'authorizer', 'employee'])
        ->orderBy('entry_date', 'desc')
        ->get();

        $exportData = $entries->map(function ($entry) {
            return [
                'Date' => $entry->entry_date->format('Y-m-d'),
                'Type' => ucfirst($entry->transaction_type),
                'Amount' => number_format($entry->amount, 2),
                'Description' => $entry->description,
                'Receiver/Beneficiary' => $entry->receiver_beneficiary ?? 'N/A',
                'Employee' => $entry->employee->name ?? 'N/A',
                'Category' => $entry->category ?? 'N/A',
                'Receipt Number' => $entry->receipt_number ?? 'N/A',
                'Approved By' => $entry->approved_by ?? ($entry->approver->name ?? 'N/A'),
                'Authorization Status' => $entry->authorization_status_label ?? 'Pending',
                'Authorized By' => $entry->authorizer->name ?? 'N/A',
                'Authorized At' => $entry->authorized_at ? $entry->authorized_at->format('Y-m-d H:i:s') : 'N/A',
                'Authorization Notes' => $entry->authorization_notes ?? '',
                'Notes' => $entry->notes ?? '',
                'Created By' => $entry->creator->name ?? 'N/A',
            ];
        });

        $filename = 'petty_cash_' . date('Y-m-d_His') . '.csv';
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

    /**
     * Authorize a petty cash entry
     */
    public function authorize(Request $request, string $id)
    {
        // Only admins and super-admins can authorize
        if (!auth()->user() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin'))) {
            abort(403, 'Unauthorized action. Only admins can authorize entries.');
        }

        $entry = PettyCashEntry::findOrFail($id);

        $request->validate([
            'authorization_status' => 'required|in:authorized,unauthorized',
            'authorization_notes' => 'nullable|string',
        ]);

        $entry->update([
            'authorization_status' => $request->authorization_status,
            'authorized_by_user_id' => auth()->id(),
            'authorized_at' => now(),
            'authorization_notes' => $request->authorization_notes,
        ]);

        $statusLabel = $request->authorization_status === 'authorized' ? 'authorized' : 'unauthorized';
        
        return redirect()->route('petty-cash.index')
            ->with('success', "Petty cash entry has been {$statusLabel} successfully!");
    }

    /**
     * Get employee petty cash transactions
     */
    public function employeeTransactions(Request $request, $employeeId)
    {
        $employee = Employees::findOrFail($employeeId);
        
        $query = $request->input('search');
        $type = $request->input('type');
        $authorizationStatus = $request->input('authorization_status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $entries = PettyCashEntry::where('employee_id', $employeeId)
            ->when($query, function ($q) use ($query) {
                $q->where('description', 'LIKE', "%{$query}%")
                  ->orWhere('receipt_number', 'LIKE', "%{$query}%");
            })
            ->when($type, function ($q) use ($type) {
                $q->where('transaction_type', $type);
            })
            ->when($authorizationStatus, function ($q) use ($authorizationStatus) {
                $q->where('authorization_status', $authorizationStatus);
            })
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereDate('entry_date', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereDate('entry_date', '<=', $endDate);
            })
            ->with(['creator', 'approver', 'authorizer'])
            ->orderBy('entry_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate totals (only authorized)
        $totalExpenses = PettyCashEntry::where('employee_id', $employeeId)
            ->where('transaction_type', 'expense')
            ->where('authorization_status', 'authorized')
            ->sum('amount');
        $totalReplenishments = PettyCashEntry::where('employee_id', $employeeId)
            ->where('transaction_type', 'replenishment')
            ->where('authorization_status', 'authorized')
            ->sum('amount');
        $netAmount = $totalReplenishments - $totalExpenses;

        return view('finance.petty-cash.employee-transactions', compact('employee', 'entries', 'totalExpenses', 'totalReplenishments', 'netAmount'));
    }

    /**
     * Export employee petty cash transactions
     */
    public function exportEmployeeTransactions(Request $request, $employeeId)
    {
        $employee = Employees::findOrFail($employeeId);
        
        $query = $request->input('search');
        $type = $request->input('type');
        $authorizationStatus = $request->input('authorization_status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $entries = PettyCashEntry::where('employee_id', $employeeId)
            ->when($query, function ($q) use ($query) {
                $q->where('description', 'LIKE', "%{$query}%")
                  ->orWhere('receipt_number', 'LIKE', "%{$query}%");
            })
            ->when($type, function ($q) use ($type) {
                $q->where('transaction_type', $type);
            })
            ->when($authorizationStatus, function ($q) use ($authorizationStatus) {
                $q->where('authorization_status', $authorizationStatus);
            })
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereDate('entry_date', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereDate('entry_date', '<=', $endDate);
            })
            ->with(['creator', 'approver', 'authorizer'])
            ->orderBy('entry_date', 'desc')
            ->get();

        $exportData = $entries->map(function ($entry) {
            return [
                'Date' => $entry->entry_date->format('Y-m-d'),
                'Type' => ucfirst($entry->transaction_type),
                'Amount' => number_format($entry->amount, 2),
                'Description' => $entry->description,
                'Receiver/Beneficiary' => $entry->receiver_beneficiary ?? 'N/A',
                'Category' => $entry->category ?? 'N/A',
                'Receipt Number' => $entry->receipt_number ?? 'N/A',
                'Approved By' => $entry->approved_by ?? ($entry->approver->name ?? 'N/A'),
                'Authorization Status' => $entry->authorization_status_label ?? 'Pending',
                'Authorized By' => $entry->authorizer->name ?? 'N/A',
                'Authorized At' => $entry->authorized_at ? $entry->authorized_at->format('Y-m-d H:i:s') : 'N/A',
                'Notes' => $entry->notes ?? '',
                'Created By' => $entry->creator->name ?? 'N/A',
            ];
        });

        $filename = 'petty_cash_employee_' . $employee->name . '_' . date('Y-m-d_His') . '.csv';
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
