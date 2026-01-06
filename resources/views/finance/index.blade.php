@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Finance Management</h1>

    <!-- Navigation Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="finance-tabs" role="tablist">
            <li class="me-2" role="presentation">
                <a href="{{ route('cash-book.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('cash-book.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}" id="cash-book-tab" type="button" role="tab" aria-controls="cash-book" aria-selected="{{ request()->routeIs('cash-book.*') ? 'true' : 'false' }}">Cash Book</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('petty-cash.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('petty-cash.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}" id="petty-cash-tab" type="button" role="tab" aria-controls="petty-cash" aria-selected="{{ request()->routeIs('petty-cash.*') ? 'true' : 'false' }}">Petty Cash</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('sales-book.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('sales-book.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}" id="sales-book-tab" type="button" role="tab" aria-controls="sales-book" aria-selected="{{ request()->routeIs('sales-book.*') ? 'true' : 'false' }}">Sales Book</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('purchases-book.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('purchases-book.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}" id="purchases-book-tab" type="button" role="tab" aria-controls="purchases-book" aria-selected="{{ request()->routeIs('purchases-book.*') ? 'true' : 'false' }}">Purchases Book</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('ar-ledger.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('ar-ledger.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}" id="ar-ledger-tab" type="button" role="tab" aria-controls="ar-ledger" aria-selected="{{ request()->routeIs('ar-ledger.*') ? 'true' : 'false' }}">AR Ledger</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('ap-ledger.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('ap-ledger.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}" id="ap-ledger-tab" type="button" role="tab" aria-controls="ap-ledger" aria-selected="{{ request()->routeIs('ap-ledger.*') ? 'true' : 'false' }}">AP Ledger</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('payroll-book.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('payroll-book.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}" id="payroll-book-tab" type="button" role="tab" aria-controls="payroll-book" aria-selected="{{ request()->routeIs('payroll-book.*') ? 'true' : 'false' }}">Payroll Book</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('general-ledger.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('general-ledger.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}" id="general-ledger-tab" type="button" role="tab" aria-controls="general-ledger" aria-selected="{{ request()->routeIs('general-ledger.*') ? 'true' : 'false' }}">General Ledger</a>
            </li>
        </ul>
    </div>

    <!-- Financial Books Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Cash Book Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Cash Book</h3>
                <a href="{{ route('cash-book.index') }}" class="text-red-600 hover:text-red-700 font-medium">View →</a>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                Records all cash receipts and payments. Tracks money coming into and going out of the company's cash account. Used to maintain a running balance of available cash and monitor cash flow.
            </p>
            <div class="flex gap-2">
                <a href="{{ route('cash-book.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700 transition">Add Entry</a>
            </div>
        </div>

        <!-- Petty Cash Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Petty Cash</h3>
                <a href="{{ route('petty-cash.index') }}" class="text-red-600 hover:text-red-700 font-medium">View →</a>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                Tracks small, day-to-day cash expenses that don't require formal invoices. Used for minor purchases like office supplies, refreshments, or small repairs. Helps maintain accountability for small cash disbursements.
            </p>
            <div class="flex gap-2">
                <a href="{{ route('petty-cash.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700 transition">Add Entry</a>
            </div>
        </div>

        <!-- Sales Book Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Sales Book</h3>
                <a href="{{ route('sales-book.index') }}" class="text-red-600 hover:text-red-700 font-medium">View →</a>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                Records all sales transactions made by the company. Tracks revenue from goods sold or services rendered to clients. Includes invoice details, payment status, and helps monitor sales performance.
            </p>
            <div class="flex gap-2">
                <a href="{{ route('sales-book.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700 transition">Add Entry</a>
            </div>
        </div>

        <!-- Purchases Book Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Purchases Book</h3>
                <a href="{{ route('purchases-book.index') }}" class="text-red-600 hover:text-red-700 font-medium">View →</a>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                Records all purchases made by the company from suppliers. Tracks expenses for inventory, equipment, services, and other business purchases. Helps monitor spending and manage supplier relationships.
            </p>
            <div class="flex gap-2">
                <a href="{{ route('purchases-book.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700 transition">Add Entry</a>
            </div>
        </div>

        <!-- AR Ledger Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Accounts Receivable (AR)</h3>
                <a href="{{ route('ar-ledger.index') }}" class="text-red-600 hover:text-red-700 font-medium">View →</a>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                Tracks money owed to the company by clients/customers. Records outstanding invoices, payment due dates, and payment status. Helps manage collections and identify overdue accounts.
            </p>
            <div class="flex gap-2">
                <a href="{{ route('ar-ledger.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700 transition">Add Entry</a>
            </div>
        </div>

        <!-- AP Ledger Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Accounts Payable (AP)</h3>
                <a href="{{ route('ap-ledger.index') }}" class="text-red-600 hover:text-red-700 font-medium">View →</a>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                Tracks money the company owes to suppliers and vendors. Records outstanding bills, payment due dates, and payment status. Helps manage payment schedules and avoid late payment penalties.
            </p>
            <div class="flex gap-2">
                <a href="{{ route('ap-ledger.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700 transition">Add Entry</a>
            </div>
        </div>

        <!-- Payroll Book Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Payroll Book</h3>
                <a href="{{ route('payroll-book.index') }}" class="text-red-600 hover:text-red-700 font-medium">View →</a>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                Records all employee salary and wage payments. Tracks basic salary, allowances, deductions (tax, pension, etc.), and net pay. Helps manage payroll expenses and ensure timely employee payments.
            </p>
            <div class="flex gap-2">
                <a href="{{ route('payroll-book.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700 transition">Add Entry</a>
            </div>
        </div>

        <!-- General Ledger Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-800">General Ledger</h3>
                <a href="{{ route('general-ledger.index') }}" class="text-red-600 hover:text-red-700 font-medium">View →</a>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                A comprehensive view of all financial transactions across all books. Provides a unified ledger showing debits, credits, and running balances. Essential for financial analysis, reporting, and auditing purposes.
            </p>
            <div class="flex gap-2">
                <a href="{{ route('general-ledger.index') }}" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700 transition">View Ledger</a>
            </div>
        </div>
    </div>
</div>
@endsection

