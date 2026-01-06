<?php

namespace App\Http\Controllers;

use App\Models\PayrollBookEntry;
use App\Models\Employees;
use Illuminate\Http\Request;

class PayrollBookController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $entries = PayrollBookEntry::when($query, function ($q) use ($query) {
            $q->where('employee_name', 'LIKE', "%{$query}%")
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
        ->with(['creator', 'employee'])
        ->orderBy('entry_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        $totalPayroll = PayrollBookEntry::sum('net_pay');
        $paidPayroll = PayrollBookEntry::where('payment_status', 'paid')->sum('net_pay');
        $pendingPayroll = PayrollBookEntry::where('payment_status', 'pending')->sum('net_pay');

        return view('finance.payroll-book.index', compact('entries', 'totalPayroll', 'paidPayroll', 'pendingPayroll'));
    }

    public function create()
    {
        $employees = Employees::where('status', 'active')->orderBy('name')->get();
        return view('finance.payroll-book.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date|after_or_equal:pay_period_start',
            'employee_id' => 'nullable|exists:employees,id',
            'employee_name' => 'required|string|max:255',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'net_pay' => 'required|numeric|min:0',
            'payment_method' => 'required|in:bank_transfer,cash,cheque',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'payment_status' => 'required|in:pending,paid,failed',
            'payment_date' => 'nullable|date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        PayrollBookEntry::create([
            'entry_date' => $request->entry_date,
            'pay_period_start' => $request->pay_period_start,
            'pay_period_end' => $request->pay_period_end,
            'employee_id' => $request->employee_id,
            'employee_name' => $request->employee_name,
            'basic_salary' => $request->basic_salary,
            'allowances' => $request->allowances ?? 0,
            'deductions' => $request->deductions ?? 0,
            'net_pay' => $request->net_pay,
            'payment_method' => $request->payment_method,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'payment_status' => $request->payment_status,
            'payment_date' => $request->payment_date,
            'description' => $request->description,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('payroll-book.index')
            ->with('success', 'Payroll book entry created successfully!');
    }

    public function show(string $id)
    {
        $entry = PayrollBookEntry::with(['creator', 'employee'])->findOrFail($id);
        return view('finance.payroll-book.show', compact('entry'));
    }

    public function edit(string $id)
    {
        $entry = PayrollBookEntry::findOrFail($id);
        $employees = Employees::where('status', 'active')->orderBy('name')->get();
        return view('finance.payroll-book.edit', compact('entry', 'employees'));
    }

    public function update(Request $request, string $id)
    {
        $entry = PayrollBookEntry::findOrFail($id);

        $request->validate([
            'entry_date' => 'required|date',
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date|after_or_equal:pay_period_start',
            'employee_id' => 'nullable|exists:employees,id',
            'employee_name' => 'required|string|max:255',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'net_pay' => 'required|numeric|min:0',
            'payment_method' => 'required|in:bank_transfer,cash,cheque',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'payment_status' => 'required|in:pending,paid,failed',
            'payment_date' => 'nullable|date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $entry->update($request->all());

        return redirect()->route('payroll-book.index')
            ->with('success', 'Payroll book entry updated successfully!');
    }

    public function destroy(string $id)
    {
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized action.');
        }

        $entry = PayrollBookEntry::findOrFail($id);
        $entry->delete();

        return redirect()->route('payroll-book.index')
            ->with('success', 'Payroll book entry deleted successfully!');
    }

    public function export(Request $request)
    {
        $query = $request->input('search');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $entries = PayrollBookEntry::when($query, function ($q) use ($query) {
            $q->where('employee_name', 'LIKE', "%{$query}%")
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
        ->with(['creator', 'employee'])
        ->orderBy('entry_date', 'desc')
        ->get();

        $exportData = $entries->map(function ($entry) {
            return [
                'Date' => $entry->entry_date->format('Y-m-d'),
                'Pay Period' => $entry->pay_period_start->format('M d, Y') . ' - ' . $entry->pay_period_end->format('M d, Y'),
                'Employee Name' => $entry->employee_name,
                'Basic Salary' => number_format($entry->basic_salary, 2),
                'Allowances' => number_format($entry->allowances, 2),
                'Deductions' => number_format($entry->deductions, 2),
                'Net Pay' => number_format($entry->net_pay, 2),
                'Payment Method' => ucfirst(str_replace('_', ' ', $entry->payment_method)),
                'Payment Status' => ucfirst($entry->payment_status),
                'Payment Date' => $entry->payment_date ? $entry->payment_date->format('Y-m-d') : 'N/A',
                'Bank Name' => $entry->bank_name ?? 'N/A',
                'Account Number' => $entry->account_number ?? 'N/A',
                'Description' => $entry->description ?? '',
                'Created By' => $entry->creator->name ?? 'N/A',
            ];
        });

        $filename = 'payroll_book_' . date('Y-m-d_His') . '.csv';
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
