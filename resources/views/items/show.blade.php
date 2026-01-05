@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $item->item_name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('items.edit', $item->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-yellow-600 transition">Edit</a>
            <a href="{{ route('items.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-gray-600 transition">Back to Items</a>
            @if(auth()->user()->hasRole('super-admin'))
            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-600 transition" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Item Details -->
    <div class="bg-white p-8 rounded-lg shadow-md mb-6">
        <h2 class="text-2xl font-semibold mb-6">Item Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Item Code (SKU)</label>
                <p class="text-lg font-semibold">{{ $item->item_code ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <p class="text-lg">
                    <span class="px-3 py-1 rounded text-sm text-white {{ $item->status_color }}">
                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                    </span>
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <p class="text-lg">{{ $item->category->name ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                <div>
                    <p class="text-lg">{{ $item->supplier->supplier_name ?? 'N/A' }}</p>
                    @if($item->supplier && $item->supplier->phone)
                        <p class="text-sm text-gray-500">{{ $item->supplier->phone }}</p>
                    @endif
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                <p class="text-lg font-semibold {{ $item->quantity <= $item->min_stock_level ? 'text-red-600' : '' }}">
                    {{ $item->quantity }} {{ $item->unit }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Stock Level</label>
                <p class="text-lg">{{ $item->min_stock_level }} {{ $item->unit }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price</label>
                <p class="text-lg font-semibold text-green-600">₦{{ number_format($item->unit_price, 2) }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Total Value</label>
                <p class="text-lg font-semibold text-blue-600">₦{{ number_format($item->quantity * $item->unit_price, 2) }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                <p class="text-lg">{{ $item->unit }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Is Consumable</label>
                <p class="text-lg">
                    <span class="px-3 py-1 rounded text-sm {{ $item->is_consumable ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $item->is_consumable ? 'Yes' : 'No' }}
                    </span>
                </p>
            </div>

            @if($item->purchase_date)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Date</label>
                <p class="text-lg">{{ $item->purchase_date->format('M d, Y') }}</p>
            </div>
            @endif

            @if($item->location)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                <p class="text-lg">{{ $item->location }}</p>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Created By</label>
                <p class="text-lg">{{ $item->creator->name ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Created At</label>
                <p class="text-lg">{{ $item->created_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>

        <!-- Stock Status Alert -->
        @if($item->quantity <= $item->min_stock_level)
        <div class="mt-6 p-4 rounded-lg bg-red-50 border border-red-200">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium text-red-800">
                    @if($item->quantity == 0)
                        Item is out of stock!
                    @else
                        Low stock warning! Only {{ $item->quantity }} {{ $item->unit }} remaining (minimum: {{ $item->min_stock_level }} {{ $item->unit }})
                    @endif
                </span>
            </div>
        </div>
        @endif

        <!-- Description -->
        @if($item->description)
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $item->description }}</p>
        </div>
        @endif
    </div>

    <!-- Inventory Transactions -->
    <div class="bg-white p-8 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Transaction History</h2>
            <a href="{{ route('inventory-transactions.create') }}?item_id={{ $item->id }}" class="bg-red-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-700 transition">
                Add Transaction
            </a>
        </div>

        @if($item->transactions && $item->transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-red-600 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Date</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Type</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Quantity</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Assigned To</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Expected Return</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Notes</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Created By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($item->transactions->sortByDesc('transaction_date') as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">{{ $transaction->transaction_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 rounded text-xs 
                                {{ $transaction->transaction_type == 'purchase' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $transaction->transaction_type == 'assignment' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $transaction->transaction_type == 'return' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $transaction->transaction_type == 'consumption' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $transaction->transaction_type == 'adjustment' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            ">
                                {{ $transaction->transaction_type_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold 
                            {{ in_array($transaction->transaction_type, ['purchase', 'return', 'adjustment']) && $transaction->quantity > 0 ? 'text-green-600' : '' }}
                            {{ in_array($transaction->transaction_type, ['assignment', 'consumption']) ? 'text-red-600' : '' }}
                        ">
                            {{ $transaction->quantity }} {{ $item->unit }}
                        </td>
                        <td class="px-4 py-3 text-sm">
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
                        <td class="px-4 py-3 text-sm">
                            @if($transaction->expected_return_date)
                                {{ $transaction->expected_return_date->format('M d, Y') }}
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">{{ Str::limit($transaction->notes ?? '—', 50) }}</td>
                        <td class="px-4 py-3 text-sm">{{ $transaction->creator->name ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <p>No transactions recorded for this item.</p>
            <a href="{{ route('inventory-transactions.create') }}?item_id={{ $item->id }}" class="mt-4 inline-block bg-red-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-700 transition">
                Add First Transaction
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

