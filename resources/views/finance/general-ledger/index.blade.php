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
            <li class="me-2" role="presentation">
                <a href="{{ route('general-ledger.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('general-ledger.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">General Ledger</a>
            </li>
        </ul>
    </div>

    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <h3 class="font-semibold text-blue-800 mb-2">General Ledger Description</h3>
        <p class="text-sm text-blue-700">
            A comprehensive view of all financial transactions across all books. Provides a unified ledger showing debits, credits, and running balances. Essential for financial analysis, reporting, and auditing purposes. This ledger aggregates transactions from Cash Book, Petty Cash, Sales Book, Purchases Book, AR Ledger, AP Ledger, and Payroll Book.
        </p>
    </div>

    <h2 class="text-2xl font-semibold mb-4">General Ledger</h2>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
            <h3 class="text-sm font-medium text-green-700">Total Debits</h3>
            <p class="text-2xl font-bold text-green-600">₦{{ number_format($totalDebits, 2) }}</p>
        </div>
        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
            <h3 class="text-sm font-medium text-red-700">Total Credits</h3>
            <p class="text-2xl font-bold text-red-600">₦{{ number_format($totalCredits, 2) }}</p>
        </div>
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <h3 class="text-sm font-medium text-blue-700">Net Amount</h3>
            <p class="text-2xl font-bold {{ $netAmount >= 0 ? 'text-blue-600' : 'text-red-600' }}">₦{{ number_format($netAmount, 2) }}</p>
        </div>
    </div>

    <!-- Summary by Book -->
    @if($summaryByBook->count() > 0)
    <div class="mb-6 bg-white p-4 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-4">Summary by Book</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($summaryByBook as $book => $summary)
            <div class="border border-gray-200 p-3 rounded">
                <h4 class="font-semibold text-sm mb-2">{{ $book }}</h4>
                <p class="text-xs text-gray-600">Entries: {{ $summary['count'] }}</p>
                <p class="text-xs text-green-600">Debits: ₦{{ number_format($summary['total_debits'], 2) }}</p>
                <p class="text-xs text-red-600">Credits: ₦{{ number_format($summary['total_credits'], 2) }}</p>
                <p class="text-xs font-semibold {{ $summary['net'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">Net: ₦{{ number_format($summary['net'], 2) }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow-md mb-6">
        <form action="{{ route('general-ledger.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label for="book_type" class="block text-sm font-medium text-gray-700 mb-2">Book Type</label>
                <select name="book_type" id="book_type" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="all" {{ $bookType == 'all' ? 'selected' : '' }}>All Books</option>
                    <option value="cash_book" {{ $bookType == 'cash_book' ? 'selected' : '' }}>Cash Book</option>
                    <option value="petty_cash" {{ $bookType == 'petty_cash' ? 'selected' : '' }}>Petty Cash</option>
                    <option value="sales_book" {{ $bookType == 'sales_book' ? 'selected' : '' }}>Sales Book</option>
                    <option value="purchases_book" {{ $bookType == 'purchases_book' ? 'selected' : '' }}>Purchases Book</option>
                    <option value="ar_ledger" {{ $bookType == 'ar_ledger' ? 'selected' : '' }}>AR Ledger</option>
                    <option value="ap_ledger" {{ $bookType == 'ap_ledger' ? 'selected' : '' }}>AP Ledger</option>
                    <option value="payroll_book" {{ $bookType == 'payroll_book' ? 'selected' : '' }}>Payroll Book</option>
                </select>
            </div>
            <div>
                <label for="transaction_type" class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                <select name="transaction_type" id="transaction_type" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="all" {{ $transactionType == 'all' ? 'selected' : '' }}>All Types</option>
                    <option value="receipt" {{ $transactionType == 'receipt' ? 'selected' : '' }}>Receipts</option>
                    <option value="payment" {{ $transactionType == 'payment' ? 'selected' : '' }}>Payments</option>
                    <option value="expense" {{ $transactionType == 'expense' ? 'selected' : '' }}>Expenses</option>
                    <option value="replenishment" {{ $transactionType == 'replenishment' ? 'selected' : '' }}>Replenishments</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-700 transition w-full">Filter</button>
                <a href="{{ route('general-ledger.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-gray-600 transition">Clear</a>
            </div>
        </form>
    </div>

    <!-- Export Button -->
    <div class="mb-4 flex justify-end">
        <a href="{{ route('general-ledger.export', request()->query()) }}" class="bg-green-600 text-white px-6 py-3 rounded-md shadow-md hover:bg-green-700 transition">Export to Excel</a>
    </div>

    <!-- Transactions Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-red-600 text-white">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Date</th>
                    <th class="px-4 py-3 text-left font-semibold">Book</th>
                    <th class="px-4 py-3 text-left font-semibold">Reference</th>
                    <th class="px-4 py-3 text-left font-semibold">Description</th>
                    <th class="px-4 py-3 text-left font-semibold">Entity</th>
                    <th class="px-4 py-3 text-right font-semibold">Debit</th>
                    <th class="px-4 py-3 text-right font-semibold">Credit</th>
                    <th class="px-4 py-3 text-right font-semibold">Balance</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $transaction['date']->format('M d, Y') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">{{ $transaction['book'] }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm">{{ $transaction['reference'] }}</td>
                    <td class="px-4 py-3">{{ $transaction['description'] }}</td>
                    <td class="px-4 py-3 text-sm">{{ $transaction['entity'] }}</td>
                    <td class="px-4 py-3 text-right font-semibold text-green-600">
                        {{ $transaction['debit'] > 0 ? '₦' . number_format($transaction['debit'], 2) : '-' }}
                    </td>
                    <td class="px-4 py-3 text-right font-semibold text-red-600">
                        {{ $transaction['credit'] > 0 ? '₦' . number_format($transaction['credit'], 2) : '-' }}
                    </td>
                    <td class="px-4 py-3 text-right font-semibold {{ $transaction['running_balance'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                        ₦{{ number_format($transaction['running_balance'], 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">No transactions found for the selected period.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-100 font-semibold">
                <tr>
                    <td colspan="5" class="px-4 py-3 text-right">Totals:</td>
                    <td class="px-4 py-3 text-right text-green-600">₦{{ number_format($totalDebits, 2) }}</td>
                    <td class="px-4 py-3 text-right text-red-600">₦{{ number_format($totalCredits, 2) }}</td>
                    <td class="px-4 py-3 text-right {{ $netAmount >= 0 ? 'text-blue-600' : 'text-red-600' }}">₦{{ number_format($netAmount, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection





