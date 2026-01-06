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

    <h2 class="text-2xl font-semibold mb-4">AR Ledger Entry Details</h2>

    <div class="bg-white p-8 rounded-lg shadow-md mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Entry Date</label>
                <p class="text-lg">{{ $entry->entry_date->format('M d, Y') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
                <p class="text-lg">{{ $entry->client_name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Number</label>
                <p class="text-lg">{{ $entry->invoice_number ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                <p class="text-lg">₦{{ number_format($entry->amount, 2) }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                <p class="text-lg {{ $entry->isOverdue() ? 'text-red-600 font-bold' : '' }}">{{ $entry->due_date->format('M d, Y') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <p class="text-lg">
                    <span class="px-2 py-1 rounded text-xs {{ $entry->status === 'paid' ? 'bg-green-100 text-green-800' : ($entry->status === 'overdue' ? 'bg-red-100 text-red-800' : ($entry->status === 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                        {{ $entry->status_label }}
                    </span>
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Paid Amount</label>
                <p class="text-lg">₦{{ number_format($entry->paid_amount, 2) }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Outstanding Balance</label>
                <p class="text-lg font-semibold {{ $entry->balance > 0 ? 'text-orange-600' : 'text-green-600' }}">₦{{ number_format($entry->balance, 2) }}</p>
            </div>
            @if($entry->last_payment_date)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Last Payment Date</label>
                <p class="text-lg">{{ $entry->last_payment_date->format('M d, Y') }}</p>
            </div>
            @endif
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Created By</label>
                <p class="text-lg">{{ $entry->creator->name ?? 'N/A' }}</p>
            </div>
        </div>

        @if($entry->description)
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $entry->description }}</p>
        </div>
        @endif

        @if($entry->notes)
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $entry->notes }}</p>
        </div>
        @endif
    </div>

    <div class="flex gap-4">
        <a href="{{ route('ar-ledger.edit', $entry->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-yellow-600 transition">Edit Entry</a>
        <a href="{{ route('ar-ledger.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-gray-600 transition">Back to AR Ledger</a>
    </div>
</div>
@endsection

