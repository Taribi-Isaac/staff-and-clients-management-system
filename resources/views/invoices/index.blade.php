@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-3 pb-4 sm:px-5">
    <h1 class="mb-4 text-center text-2xl font-bold sm:mb-6 sm:text-3xl">Invoices, Receipts &amp; Quotes</h1>

    <!-- Search and Filters -->
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between lg:gap-6">
        <form action="{{ route('invoices.index') }}" method="GET" class="flex w-full min-w-0 flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-stretch">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by invoice #, client, title"
                class="min-w-0 w-full rounded-lg border border-gray-300 p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:min-w-[10rem] sm:flex-1 sm:max-w-md">
            
                <select name="type" class="w-full rounded-lg border border-gray-300 p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:w-auto sm:min-w-[8.5rem]">
                <option value="">All Types</option>
                <option value="invoice" {{ request('type') == 'invoice' ? 'selected' : '' }}>Invoices</option>
                <option value="receipt" {{ request('type') == 'receipt' ? 'selected' : '' }}>Receipts</option>
                <option value="quote" {{ request('type') == 'quote' ? 'selected' : '' }}>Quotes</option>
            </select>

            <select name="status" class="w-full rounded-lg border border-gray-300 p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:w-auto sm:min-w-[9.5rem]">
                <option value="">All Statuses</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <select name="recurring" class="w-full rounded-lg border border-gray-300 p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:w-auto sm:min-w-[11rem]">
                <option value="">All Invoices</option>
                <option value="1" {{ request('recurring') == '1' ? 'selected' : '' }}>Recurring Only</option>
                <option value="0" {{ request('recurring') == '0' ? 'selected' : '' }}>Non-Recurring Only</option>
            </select>

            <div class="flex flex-wrap gap-2 sm:items-stretch">
                <button type="submit" class="flex-1 rounded-md bg-red-600 px-4 py-3 text-center text-white shadow-md transition hover:bg-red-700 sm:flex-none">Search</button>
                <a href="{{ route('invoices.index') }}" class="flex-1 rounded-md bg-gray-500 px-4 py-3 text-center text-white shadow-md transition hover:bg-gray-600 sm:flex-none">Clear</a>
            </div>
        </form>
        
        <a href="{{ route('invoices.create') }}" class="inline-flex w-full shrink-0 items-center justify-center rounded-md bg-red-600 px-4 py-3 text-center text-sm font-medium text-white shadow-md transition hover:bg-red-700 sm:w-auto sm:text-base">
            Create New Invoice/Quote
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="-mx-3 overflow-x-auto sm:mx-0 sm:rounded-lg">
        <table class="min-w-[44rem] w-full border border-gray-200 bg-white text-sm shadow-md sm:text-base">
            <thead class="bg-red-600 text-white">
                <tr>
                    <th class="px-3 py-3 text-left font-semibold sm:px-6 sm:py-4">Invoice #</th>
                    <th class="px-3 py-3 text-left font-semibold sm:px-6 sm:py-4">Type</th>
                    <th class="px-3 py-3 text-left font-semibold sm:px-6 sm:py-4">Client</th>
                    <th class="px-3 py-3 text-left font-semibold sm:px-6 sm:py-4">Date</th>
                    <th class="px-3 py-3 text-left font-semibold sm:px-6 sm:py-4">Due Date</th>
                    <th class="px-3 py-3 text-left font-semibold sm:px-6 sm:py-4">Total</th>
                    <th class="px-3 py-3 text-left font-semibold sm:px-6 sm:py-4">Status</th>
                    <th class="px-3 py-3 text-left font-semibold sm:px-6 sm:py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-50">
                    <td class="max-w-[10rem] px-3 py-3 align-top font-medium sm:max-w-none sm:px-6 sm:py-4">
                        {{ $invoice->invoice_number }}
                        @if($invoice->is_recurring)
                            <span class="ml-2 px-2 py-1 bg-indigo-100 text-indigo-800 rounded text-xs font-semibold" title="Recurring Invoice">
                                🔄 Recurring
                            </span>
                            @if($invoice->is_recurring_paused)
                                <span class="ml-1 px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs" title="Paused">
                                    ⏸️ Paused
                                </span>
                            @endif
                        @endif
                    </td>
                    <td class="px-3 py-3 sm:px-6 sm:py-4">
                        @php
                            $typeColors = [
                                'invoice' => 'bg-blue-100 text-blue-800',
                                'receipt' => 'bg-green-100 text-green-800',
                                'quote' => 'bg-purple-100 text-purple-800',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded text-xs {{ $typeColors[$invoice->type] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($invoice->type) }}
                        </span>
                    </td>
                    <td class="max-w-[8rem] break-words px-3 py-3 sm:max-w-none sm:px-6 sm:py-4">{{ $invoice->client_name ?? 'N/A' }}</td>
                    <td class="whitespace-nowrap px-3 py-3 sm:px-6 sm:py-4">{{ $invoice->invoice_date->format('M d, Y') }}</td>
                    <td class="px-3 py-3 sm:px-6 sm:py-4">
                        {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}
                        @if($invoice->is_recurring && $invoice->next_recurring_date)
                            <div class="text-xs text-indigo-600 mt-1">
                                Next: {{ $invoice->next_recurring_date->format('M d, Y') }}
                            </div>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-3 font-semibold sm:px-6 sm:py-4">₦{{ number_format($invoice->total, 2) }}</td>
                    <td class="px-3 py-3 sm:px-6 sm:py-4">
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
                    <td class="min-w-[7.5rem] px-3 py-3 sm:px-6 sm:py-4">
                        <div class="flex flex-col gap-1">
                            <a href="{{ route('invoices.show', $invoice->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">View</a>
                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 transition">Edit</a>
                            <a href="{{ route('invoices.duplicate', $invoice->id) }}" class="bg-purple-500 text-white px-3 py-1 rounded text-sm hover:bg-purple-600 transition">Duplicate</a>
                            <a href="{{ route('invoices.pdf', $invoice->id) }}?v={{ $invoice->updated_at?->timestamp ?? $invoice->id }}" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition">PDF</a>
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
                    <td colspan="8" class="px-3 py-4 text-center text-gray-500 sm:px-6">No invoices found.</td>
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


