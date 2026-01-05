@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Inventory Management</h1>

    <!-- Navigation Tabs -->
    <div class="mb-6 flex justify-center">
        <div class="flex gap-2 bg-gray-100 p-1 rounded-lg">
            <a href="{{ route('items.index') }}" class="px-6 py-2 rounded-md {{ request()->routeIs('items.*') ? 'bg-red-600 text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                Items
            </a>
            <a href="{{ route('suppliers.index') }}" class="px-6 py-2 rounded-md {{ request()->routeIs('suppliers.*') ? 'bg-red-600 text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                Suppliers
            </a>
            <a href="{{ route('categories.index') }}" class="px-6 py-2 rounded-md {{ request()->routeIs('categories.*') ? 'bg-red-600 text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                Categories
            </a>
            <a href="{{ route('inventory-transactions.index') }}" class="px-6 py-2 rounded-md {{ request()->routeIs('inventory-transactions.*') ? 'bg-red-600 text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                Transactions
            </a>
        </div>
    </div>

    <h2 class="text-2xl font-bold mb-6 text-center">Inventory Transactions</h2>

    <!-- Search and Filters -->
    <div class="flex flex-wrap justify-between mb-6 gap-4">
        <form action="{{ route('inventory-transactions.index') }}" method="GET" class="flex gap-2 flex-wrap">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by item name"
                class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            
            <select name="type" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">All Types</option>
                <option value="purchase" {{ request('type') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                <option value="assignment" {{ request('type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>Return</option>
                <option value="consumption" {{ request('type') == 'consumption' ? 'selected' : '' }}>Consumption</option>
                <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
            </select>

            <button type="submit" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">Search</button>
            <a href="{{ route('inventory-transactions.index') }}" class="bg-gray-500 text-white px-4 py-3 rounded-md shadow-md hover:bg-gray-600 transition">Clear</a>
        </form>
        
        <div>
            <a href="{{ route('inventory-transactions.create') }}" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">
                Add Transaction
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead class="bg-red-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Date</th>
                    <th class="px-6 py-4 text-left font-semibold">Item</th>
                    <th class="px-6 py-4 text-left font-semibold">Type</th>
                    <th class="px-6 py-4 text-left font-semibold">Quantity</th>
                    <th class="px-6 py-4 text-left font-semibold">Assigned To</th>
                    <th class="px-6 py-4 text-left font-semibold">Expected Return</th>
                    <th class="px-6 py-4 text-left font-semibold">Created By</th>
                    <th class="px-6 py-4 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm">{{ $transaction->transaction_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $transaction->item->item_name }}</div>
                        <div class="text-xs text-gray-500">{{ $transaction->item->item_code ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs text-white
                            {{ $transaction->transaction_type == 'purchase' ? 'bg-green-500' : '' }}
                            {{ $transaction->transaction_type == 'assignment' ? 'bg-blue-500' : '' }}
                            {{ $transaction->transaction_type == 'return' ? 'bg-purple-500' : '' }}
                            {{ $transaction->transaction_type == 'consumption' ? 'bg-orange-500' : '' }}
                            {{ $transaction->transaction_type == 'adjustment' ? 'bg-yellow-500' : '' }}
                        ">
                            {{ $transaction->transaction_type_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-semibold
                            {{ in_array($transaction->transaction_type, ['purchase', 'return']) ? 'text-green-600' : '' }}
                            {{ in_array($transaction->transaction_type, ['assignment', 'consumption']) ? 'text-red-600' : '' }}
                        ">
                            {{ $transaction->quantity }} {{ $transaction->item->unit }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($transaction->assignedUser)
                            <div>{{ $transaction->assignedUser->name }}</div>
                            <div class="text-xs text-gray-500">Staff</div>
                        @elseif($transaction->assignedClient)
                            <div>{{ $transaction->assignedClient->client_name }}</div>
                            <div class="text-xs text-gray-500">Client</div>
                        @elseif($transaction->assignedProject)
                            <div>{{ $transaction->assignedProject->project_name }}</div>
                            <div class="text-xs text-gray-500">Project</div>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($transaction->expected_return_date)
                            {{ $transaction->expected_return_date->format('M d, Y') }}
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $transaction->creator->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('inventory-transactions.show', $transaction->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No transactions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $transactions->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>
@endsection

