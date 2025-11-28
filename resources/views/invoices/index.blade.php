@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Invoices & Receipts</h1>

    <!-- Search and Filters -->
    <div class="flex flex-wrap justify-between mb-6 gap-4">
        <form action="{{ route('invoices.index') }}" method="GET" class="flex gap-2 flex-wrap">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by invoice number, client, or title"
                class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            
            <select name="type" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">All Types</option>
                <option value="invoice" {{ request('type') == 'invoice' ? 'selected' : '' }}>Invoices</option>
                <option value="receipt" {{ request('type') == 'receipt' ? 'selected' : '' }}>Receipts</option>
            </select>

            <select name="status" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">All Statuses</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <button type="submit" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">Search</button>
            <a href="{{ route('invoices.index') }}" class="bg-gray-500 text-white px-4 py-3 rounded-md shadow-md hover:bg-gray-600 transition">Clear</a>
        </form>
        
        <a href="{{ route('invoices.create') }}" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">
            Create New Invoice
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead class="bg-red-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Invoice #</th>
                    <th class="px-6 py-4 text-left font-semibold">Type</th>
                    <th class="px-6 py-4 text-left font-semibold">Client</th>
                    <th class="px-6 py-4 text-left font-semibold">Date</th>
                    <th class="px-6 py-4 text-left font-semibold">Due Date</th>
                    <th class="px-6 py-4 text-left font-semibold">Total</th>
                    <th class="px-6 py-4 text-left font-semibold">Status</th>
                    <th class="px-6 py-4 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">{{ $invoice->invoice_number }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs {{ $invoice->type == 'invoice' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($invoice->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $invoice->client_name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $invoice->invoice_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</td>
                    <td class="px-6 py-4 font-semibold">₦{{ number_format($invoice->total, 2) }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'sent' => 'bg-blue-100 text-blue-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'overdue' => 'bg-red-100 text-red-800',
                                'cancelled' => 'bg-yellow-100 text-yellow-800',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded text-xs {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            <a href="{{ route('invoices.show', $invoice->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">View</a>
                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 transition">Edit</a>
                            <a href="{{ route('invoices.duplicate', $invoice->id) }}" class="bg-purple-500 text-white px-3 py-1 rounded text-sm hover:bg-purple-600 transition">Duplicate</a>
                            <a href="{{ route('invoices.pdf', $invoice->id) }}" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition">PDF</a>
                            @if(auth()->user()->hasRole('super-admin'))
                            <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition w-full" onclick="return confirm('Are you sure you want to delete this invoice?')">Delete</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No invoices found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-6">
        {{ $invoices->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>
@endsection


