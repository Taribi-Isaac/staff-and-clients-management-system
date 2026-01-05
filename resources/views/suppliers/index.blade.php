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

    <h2 class="text-2xl font-bold mb-6 text-center">Suppliers Management</h2>

    <!-- Search and Actions -->
    <div class="flex flex-wrap justify-between mb-6 gap-4">
        <form action="{{ route('suppliers.index') }}" method="GET" class="flex gap-2 flex-wrap">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search suppliers..."
                class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            <button type="submit" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">Search</button>
            <a href="{{ route('suppliers.index') }}" class="bg-gray-500 text-white px-4 py-3 rounded-md shadow-md hover:bg-gray-600 transition">Clear</a>
        </form>
        
        <div>
            <a href="{{ route('suppliers.create') }}" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">
                Add Supplier
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
                    <th class="px-6 py-4 text-left font-semibold">Supplier Name</th>
                    <th class="px-6 py-4 text-left font-semibold">Contact Person</th>
                    <th class="px-6 py-4 text-left font-semibold">Phone</th>
                    <th class="px-6 py-4 text-left font-semibold">Email</th>
                    <th class="px-6 py-4 text-left font-semibold">Address</th>
                    <th class="px-6 py-4 text-left font-semibold">Items Count</th>
                    <th class="px-6 py-4 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($suppliers as $supplier)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">{{ $supplier->supplier_name }}</td>
                    <td class="px-6 py-4">{{ $supplier->contact_person ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $supplier->phone ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $supplier->email ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ Str::limit($supplier->address ?? 'N/A', 50) }}</td>
                    <td class="px-6 py-4">{{ $supplier->items_count }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">
                                Edit
                            </a>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No suppliers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $suppliers->links() }}
    </div>
</div>
@endsection

