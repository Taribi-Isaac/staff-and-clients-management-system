@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <div class="mb-6">
        <a href="{{ route('petty-cash.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Back to Petty Cash</a>
        <h1 class="text-3xl font-bold mb-2">Petty Cash Transactions for {{ $employee->name }}</h1>
        <p class="text-gray-600">{{ $employee->email }} | {{ $employee->phone }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
            <h3 class="text-sm font-medium text-red-700">Total Expenses (Authorized)</h3>
            <p class="text-2xl font-bold text-red-600">₦{{ number_format($totalExpenses, 2) }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
            <h3 class="text-sm font-medium text-green-700">Total Replenishments (Authorized)</h3>
            <p class="text-2xl font-bold text-green-600">₦{{ number_format($totalReplenishments, 2) }}</p>
        </div>
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <h3 class="text-sm font-medium text-blue-700">Net Amount (Deductible)</h3>
            <p class="text-2xl font-bold text-blue-600">₦{{ number_format($netAmount, 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Positive = Amount to deduct from salary</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="flex flex-wrap justify-between mb-6 gap-4">
        <form action="{{ route('petty-cash.employee', $employee->id) }}" method="GET" class="flex gap-2 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            <select name="type" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">All Types</option>
                <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                <option value="replenishment" {{ request('type') == 'replenishment' ? 'selected' : '' }}>Replenishment</option>
            </select>
            <select name="authorization_status" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">All Authorization Status</option>
                <option value="pending" {{ request('authorization_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="authorized" {{ request('authorization_status') == 'authorized' ? 'selected' : '' }}>Authorized</option>
                <option value="unauthorized" {{ request('authorization_status') == 'unauthorized' ? 'selected' : '' }}>Unauthorized</option>
            </select>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            <button type="submit" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">Search</button>
            <a href="{{ route('petty-cash.employee', $employee->id) }}" class="bg-gray-500 text-white px-4 py-3 rounded-md shadow-md hover:bg-gray-600 transition">Clear</a>
        </form>
        <div class="flex gap-2">
            <a href="{{ route('petty-cash.employee.export', ['employeeId' => $employee->id] + request()->query()) }}" class="bg-green-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-green-700 transition">Export CSV</a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead class="bg-red-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Date</th>
                    <th class="px-6 py-4 text-left font-semibold">Type</th>
                    <th class="px-6 py-4 text-left font-semibold">Description</th>
                    <th class="px-6 py-4 text-left font-semibold">Amount</th>
                    <th class="px-6 py-4 text-left font-semibold">Authorization</th>
                    <th class="px-6 py-4 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($entries as $entry)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $entry->entry_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs {{ $entry->transaction_type === 'expense' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $entry->transaction_type_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $entry->description }}</td>
                    <td class="px-6 py-4 font-semibold">₦{{ number_format($entry->amount, 2) }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusColor = match($entry->authorization_status ?? 'pending') {
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'authorized' => 'bg-green-100 text-green-800',
                                'unauthorized' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800',
                            };
                        @endphp
                        <span class="px-2 py-1 rounded text-xs {{ $statusColor }}">
                            {{ $entry->authorization_status_label ?? 'Pending' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('petty-cash.show', $entry->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No transactions found for this employee.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $entries->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>
@endsection





