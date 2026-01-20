@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Finance Management</h1>

    <!-- Navigation Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="finance-tabs" role="tablist">
            <li class="me-2" role="presentation">
                <a href="{{ route('cash-book.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('cash-book.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">Cash Book</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('petty-cash.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('petty-cash.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">Petty Cash</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('sales-book.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('sales-book.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">Sales Book</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('purchases-book.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('purchases-book.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">Purchases Book</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('ar-ledger.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('ar-ledger.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">AR Ledger</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('ap-ledger.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('ap-ledger.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">AP Ledger</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('payroll-book.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('payroll-book.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">Payroll Book</a>
            </li>
        </ul>
    </div>

    <h2 class="text-2xl font-semibold mb-4">Add Payroll Book Entry</h2>

    <form action="{{ route('payroll-book.store') }}" method="POST" class="bg-white p-8 rounded-lg shadow-md">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="entry_date" class="block text-sm font-medium text-gray-700 mb-2">Entry Date *</label>
                <input type="date" name="entry_date" id="entry_date" value="{{ old('entry_date', date('Y-m-d')) }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('entry_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">Employee (Optional)</label>
                <select name="employee_id" id="employee_id" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select Employee --</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
                @error('employee_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="employee_name" class="block text-sm font-medium text-gray-700 mb-2">Employee Name *</label>
                <input type="text" name="employee_name" id="employee_name" value="{{ old('employee_name') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('employee_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="pay_period_start" class="block text-sm font-medium text-gray-700 mb-2">Pay Period Start *</label>
                <input type="date" name="pay_period_start" id="pay_period_start" value="{{ old('pay_period_start') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('pay_period_start')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="pay_period_end" class="block text-sm font-medium text-gray-700 mb-2">Pay Period End *</label>
                <input type="date" name="pay_period_end" id="pay_period_end" value="{{ old('pay_period_end') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('pay_period_end')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="basic_salary" class="block text-sm font-medium text-gray-700 mb-2">Basic Salary *</label>
                <input type="number" name="basic_salary" id="basic_salary" value="{{ old('basic_salary') }}" step="0.01" min="0" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('basic_salary')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="allowances" class="block text-sm font-medium text-gray-700 mb-2">Allowances</label>
                <input type="number" name="allowances" id="allowances" value="{{ old('allowances', 0) }}" step="0.01" min="0" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('allowances')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="deductions" class="block text-sm font-medium text-gray-700 mb-2">Deductions</label>
                <input type="number" name="deductions" id="deductions" value="{{ old('deductions', 0) }}" step="0.01" min="0" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('deductions')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="net_pay" class="block text-sm font-medium text-gray-700 mb-2">Net Pay *</label>
                <input type="number" name="net_pay" id="net_pay" value="{{ old('net_pay') }}" step="0.01" min="0" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('net_pay')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                <select name="payment_method" id="payment_method" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                </select>
                @error('payment_method')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('bank_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('account_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">Payment Status *</label>
                <select name="payment_status" id="payment_status" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ old('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                @error('payment_status')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Payment Date</label>
                <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('payment_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" id="notes" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('payroll-book.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-md shadow-md hover:bg-gray-600 transition">Cancel</a>
            <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-md shadow-md hover:bg-red-700 transition">Create Entry</button>
        </div>
    </form>

    <script>
        // Auto-calculate net pay
        document.addEventListener('DOMContentLoaded', function() {
            const basicSalaryInput = document.getElementById('basic_salary');
            const allowancesInput = document.getElementById('allowances');
            const deductionsInput = document.getElementById('deductions');
            const netPayInput = document.getElementById('net_pay');

            function calculateNetPay() {
                const basic = parseFloat(basicSalaryInput.value) || 0;
                const allowances = parseFloat(allowancesInput.value) || 0;
                const deductions = parseFloat(deductionsInput.value) || 0;
                const netPay = basic + allowances - deductions;
                netPayInput.value = Math.max(0, netPay).toFixed(2);
            }

            basicSalaryInput.addEventListener('input', calculateNetPay);
            allowancesInput.addEventListener('input', calculateNetPay);
            deductionsInput.addEventListener('input', calculateNetPay);
        });
    </script>
</div>
@endsection





