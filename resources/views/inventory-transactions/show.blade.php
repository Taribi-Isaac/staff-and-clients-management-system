@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Transaction Details</h1>
        <div class="flex gap-2">
            <a href="{{ route('inventory-transactions.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-gray-600 transition">Back to Transactions</a>
        </div>
    </div>

    <!-- Transaction Details -->
    <div class="bg-white p-8 rounded-lg shadow-md mb-6">
        <h2 class="text-2xl font-semibold mb-6">Transaction Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Date</label>
                <p class="text-lg">{{ $transaction->transaction_date->format('M d, Y') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Type</label>
                <p class="text-lg">
                    <span class="px-3 py-1 rounded text-sm text-white
                        {{ $transaction->transaction_type == 'purchase' ? 'bg-green-500' : '' }}
                        {{ $transaction->transaction_type == 'assignment' ? 'bg-blue-500' : '' }}
                        {{ $transaction->transaction_type == 'return' ? 'bg-purple-500' : '' }}
                        {{ $transaction->transaction_type == 'consumption' ? 'bg-orange-500' : '' }}
                        {{ $transaction->transaction_type == 'adjustment' ? 'bg-yellow-500' : '' }}
                    ">
                        {{ $transaction->transaction_type_label }}
                    </span>
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Item</label>
                <div>
                    <p class="text-lg font-semibold">{{ $transaction->item->item_name }}</p>
                    <p class="text-sm text-gray-500">{{ $transaction->item->item_code ?? 'N/A' }}</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                <p class="text-lg font-semibold 
                    {{ in_array($transaction->transaction_type, ['purchase', 'return']) ? 'text-green-600' : '' }}
                    {{ in_array($transaction->transaction_type, ['assignment', 'consumption']) ? 'text-red-600' : '' }}
                ">
                    {{ $transaction->quantity }} {{ $transaction->item->unit }}
                </p>
            </div>

            @if($transaction->assignedUser || $transaction->assignedClient || $transaction->assignedProject)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                @if($transaction->assignedUser)
                    <div>
                        <p class="text-lg font-semibold">{{ $transaction->assignedUser->name }}</p>
                        <p class="text-sm text-gray-500">Staff Member</p>
                    </div>
                @elseif($transaction->assignedClient)
                    <div>
                        <p class="text-lg font-semibold">{{ $transaction->assignedClient->client_name }}</p>
                        <p class="text-sm text-gray-500">Client</p>
                    </div>
                @elseif($transaction->assignedProject)
                    <div>
                        <p class="text-lg font-semibold">{{ $transaction->assignedProject->project_name }}</p>
                        <p class="text-sm text-gray-500">Project</p>
                    </div>
                @endif
            </div>
            @endif

            @if($transaction->expected_return_date)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Expected Return Date</label>
                <p class="text-lg">{{ $transaction->expected_return_date->format('M d, Y') }}</p>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Created By</label>
                <p class="text-lg">{{ $transaction->creator->name ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Created At</label>
                <p class="text-lg">{{ $transaction->created_at->format('M d, Y h:i A') }}</p>
            </div>

            @if($transaction->notes)
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $transaction->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Item Information -->
    <div class="bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-6">Item Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Stock</label>
                <p class="text-lg font-semibold">{{ $transaction->item->quantity }} {{ $transaction->item->unit }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <p class="text-lg">
                    <span class="px-3 py-1 rounded text-sm text-white {{ $transaction->item->status_color }}">
                        {{ ucfirst(str_replace('_', ' ', $transaction->item->status)) }}
                    </span>
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <p class="text-lg">{{ $transaction->item->category->name ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                <p class="text-lg">{{ $transaction->item->supplier->supplier_name ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('items.show', $transaction->item->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600 transition">
                View Item Details
            </a>
        </div>
    </div>
</div>
@endsection

