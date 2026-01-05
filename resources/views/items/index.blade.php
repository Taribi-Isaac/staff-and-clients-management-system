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

    <!-- Search and Filters -->
    <div class="flex flex-wrap justify-between mb-6 gap-4">
        <form action="{{ route('items.index') }}" method="GET" class="flex gap-2 flex-wrap">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by item name or code"
                class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            
            <select name="category" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="status" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">All Statuses</option>
                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                <option value="discontinued" {{ request('status') == 'discontinued' ? 'selected' : '' }}>Discontinued</option>
            </select>

            <button type="submit" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">Search</button>
            <a href="{{ route('items.index') }}" class="bg-gray-500 text-white px-4 py-3 rounded-md shadow-md hover:bg-gray-600 transition">Clear</a>
        </form>
        
        <div class="flex gap-2">
            <a href="{{ route('items.create') }}" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">
                Add Item
            </a>
            <a href="{{ route('items.template.download') }}" class="bg-green-600 text-black px-4 py-3 rounded-md shadow-md hover:bg-green-700 transition">
                Download CSV Template
            </a>
            <button onclick="document.getElementById('bulkUploadModal').classList.remove('hidden')" class="bg-blue-600 text-black px-4 py-3 rounded-md shadow-md hover:bg-blue-700 transition">
                Bulk Upload CSV
            </button>
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
                    <th class="px-6 py-4 text-left font-semibold">Item Name</th>
                    <th class="px-6 py-4 text-left font-semibold">Code</th>
                    <th class="px-6 py-4 text-left font-semibold">Category</th>
                    <th class="px-6 py-4 text-left font-semibold">Supplier</th>
                    <th class="px-6 py-4 text-left font-semibold">Quantity</th>
                    <th class="px-6 py-4 text-left font-semibold">Unit Price</th>
                    <th class="px-6 py-4 text-left font-semibold">Status</th>
                    <th class="px-6 py-4 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($items as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">{{ $item->item_name }}</td>
                    <td class="px-6 py-4">{{ $item->item_code ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $item->category->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        @if($item->supplier)
                            <div>{{ $item->supplier->supplier_name }}</div>
                            @if($item->supplier->phone)
                                <div class="text-xs text-gray-500">{{ $item->supplier->phone }}</div>
                            @endif
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="{{ $item->quantity <= $item->min_stock_level ? 'text-red-600 font-bold' : '' }}">
                            {{ $item->quantity }} {{ $item->unit }}
                        </span>
                    </td>
                    <td class="px-6 py-4">₦{{ number_format($item->unit_price, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs {{ $item->status_color }}">
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            <a href="{{ route('items.show', $item->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">View</a>
                            <a href="{{ route('items.edit', $item->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 transition">Edit</a>
                            @if(auth()->user()->hasRole('super-admin'))
                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition w-full" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No items found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $items->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>

<!-- Bulk Upload Modal -->
<div id="bulkUploadModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Bulk Upload Items (CSV)</h3>
            <form action="{{ route('items.bulk-upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">CSV File</label>
                    <input type="file" name="csv_file" accept=".csv,.txt" required class="w-full p-2 border border-gray-300 rounded-lg">
                    <p class="mt-2 text-xs text-gray-500">
                        CSV Format: item_name, item_code, category_name, supplier_name, quantity, unit, unit_price, purchase_date, location, is_consumable (yes/no), description
                    </p>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('bulkUploadModal').classList.add('hidden')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

