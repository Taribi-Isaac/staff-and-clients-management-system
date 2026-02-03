@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ ucfirst($invoice->type) }} #{{ $invoice->invoice_number }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('invoices.edit', $invoice->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-yellow-600 transition">Edit</a>
            <a href="{{ route('invoices.duplicate', $invoice->id) }}" class="bg-purple-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-purple-600 transition">Duplicate</a>
            <a href="{{ route('invoices.pdf', $invoice->id) }}" class="bg-red-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-700 transition">Download PDF</a>
            @if(auth()->user()->hasRole('super-admin'))
            <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-600 transition" onclick="return confirm('Are you sure you want to delete this invoice?')">Delete</button>
            </form>
            @endif
        </div>
    </div>

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

    <!-- Recurring Invoice Information -->
    @if($invoice->is_recurring)
    <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 mb-6 rounded">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-lg font-semibold text-indigo-800 mb-2">🔄 Recurring Invoice</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="font-semibold text-gray-700">Frequency:</span>
                        <span class="ml-2 text-gray-600 capitalize">{{ str_replace('_', ' ', $invoice->recurring_frequency ?? 'N/A') }}</span>
                    </div>
                    @if($invoice->next_recurring_date)
                    <div>
                        <span class="font-semibold text-gray-700">Next Invoice:</span>
                        <span class="ml-2 text-gray-600">{{ $invoice->next_recurring_date->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if($invoice->recurring_end_date)
                    <div>
                        <span class="font-semibold text-gray-700">Ends:</span>
                        <span class="ml-2 text-gray-600">{{ $invoice->recurring_end_date->format('M d, Y') }}</span>
                    </div>
                    @endif
                    <div>
                        <span class="font-semibold text-gray-700">Notification:</span>
                        <span class="ml-2 text-gray-600">{{ $invoice->notification_days_before ?? 3 }} days before due</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-700">Status:</span>
                        <span class="ml-2 {{ $invoice->is_recurring_paused ? 'text-yellow-600' : 'text-green-600' }}">
                            {{ $invoice->is_recurring_paused ? '⏸️ Paused' : '▶️ Active' }}
                        </span>
                    </div>
                    @if($invoice->parent_invoice_id)
                    <div>
                        <span class="font-semibold text-gray-700">Parent Invoice:</span>
                        <a href="{{ route('invoices.show', $invoice->parent_invoice_id) }}" class="ml-2 text-indigo-600 hover:underline">
                            View Parent
                        </a>
                    </div>
                    @endif
                    @if($invoice->childInvoices->count() > 0)
                    <div>
                        <span class="font-semibold text-gray-700">Generated Invoices:</span>
                        <span class="ml-2 text-gray-600">{{ $invoice->childInvoices->count() }}</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="flex flex-col gap-2">
                @if(!$invoice->parent_invoice_id)
                <form action="{{ route('invoices.toggle-recurring', $invoice->id) }}" method="POST" class="inline">
                    @csrf
                    @if($invoice->is_recurring_paused)
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-green-600 transition text-sm">
                            ▶ Resume
                        </button>
                    @else
                        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-yellow-600 transition text-sm">
                            ⏸ Pause
                        </button>
                    @endif
                </form>
                <form action="{{ route('invoices.generate-next', $invoice->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-indigo-600 transition text-sm" onclick="return confirm('Generate next invoice for this recurring invoice?')">
                        ➕ Generate Next
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Invoice Preview -->
    <div class="bg-white p-8 rounded-lg shadow-md">
        <!-- Header -->
        <div class="flex justify-between mb-8 pb-6 border-b-2 border-red-600">
            <div>
                <div class="mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Raslordeck Limited Logo" class="h-16">
                </div>
                <h2 class="text-2xl font-bold text-red-600 mb-2">Raslordeck Limited</h2>
                <p class="text-gray-600">No 10 Ada George road, Port Harcourt.</p>
                <p class="text-gray-600">Email: info@raslordeckltd.com</p>
                <p class="text-gray-600">Phone: +2349131271958</p>
            </div>
            <div class="text-right">
                <h3 class="text-xl font-bold mb-2">{{ $invoice->title ?? ucfirst($invoice->type) }}</h3>
                <p class="text-gray-600"><strong>{{ ucfirst($invoice->type) }} #:</strong> {{ $invoice->invoice_number }}</p>
                <p class="text-gray-600"><strong>Date:</strong> {{ $invoice->invoice_date->format('M d, Y') }}</p>
                @if($invoice->due_date)
                <p class="text-gray-600"><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
                @endif
                <p class="mt-2">
                    @php
                        $statusColors = [
                            'draft' => 'bg-gray-100 text-gray-800',
                            'sent' => 'bg-blue-100 text-blue-800',
                            'paid' => 'bg-green-100 text-green-800',
                            'overdue' => 'bg-red-100 text-red-800',
                            'cancelled' => 'bg-yellow-100 text-yellow-800',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded text-sm {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Client Information -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-3 text-red-600">Bill To:</h3>
            <div class="bg-gray-50 p-4 rounded">
                <p class="font-semibold">{{ $invoice->client_name ?? 'N/A' }}</p>
                @if($invoice->client_email)
                <p class="text-gray-600">{{ $invoice->client_email }}</p>
                @endif
                @if($invoice->client_address)
                <p class="text-gray-600">{{ $invoice->client_address }}</p>
                @endif
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="mb-8">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-red-600 text-white">
                        <th class="px-4 py-3 text-left">Description</th>
                        <th class="px-4 py-3 text-right">Quantity</th>
                        <th class="px-4 py-3 text-right">Unit Price</th>
                        <th class="px-4 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $item->description }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($item->quantity, 2) }}</td>
                        <td class="px-4 py-3 text-right">₦{{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-4 py-3 text-right font-semibold">₦{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="flex justify-end mb-8">
            <div class="w-1/3">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-semibold">₦{{ number_format($invoice->subtotal, 2) }}</span>
                </div>
                @if($invoice->discount > 0)
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Discount:</span>
                    <span class="font-semibold">-₦{{ number_format($invoice->discount, 2) }}</span>
                </div>
                @endif
                @if($invoice->tax_rate > 0)
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Tax ({{ $invoice->tax_rate }}%):</span>
                    <span class="font-semibold">₦{{ number_format($invoice->tax_amount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between pt-3 border-t-2 border-gray-300">
                    <span class="text-lg font-bold">Total:</span>
                    <span class="text-lg font-bold text-red-600">₦{{ number_format($invoice->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Notes and Terms -->
        @if($invoice->notes || $invoice->terms)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            @if($invoice->notes)
            <div>
                <h3 class="text-lg font-semibold mb-2 text-red-600">Notes</h3>
                <p class="text-gray-600 whitespace-pre-line">{{ $invoice->notes }}</p>
            </div>
            @endif
            @if($invoice->terms)
            <div>
                <h3 class="text-lg font-semibold mb-2 text-red-600">Terms & Conditions</h3>
                <p class="text-gray-600 whitespace-pre-line">{{ $invoice->terms }}</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-center text-gray-500 text-sm">
            <p>Visit us: www.raslordeckltd.com</p>
        </div>
    </div>

    <!-- Child Invoices (Generated from this recurring invoice) -->
    @if($invoice->is_recurring && $invoice->childInvoices && $invoice->childInvoices->count() > 0)
    <div class="bg-white p-6 rounded-lg shadow-md mt-6">
        <h2 class="text-xl font-semibold mb-4">Generated Invoices</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Invoice #</th>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Due Date</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Total</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->childInvoices as $childInvoice)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $childInvoice->invoice_number }}</td>
                        <td class="px-4 py-2">{{ $childInvoice->invoice_date->format('M d, Y') }}</td>
                        <td class="px-4 py-2">{{ $childInvoice->due_date ? $childInvoice->due_date->format('M d, Y') : 'N/A' }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs {{ $childInvoice->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($childInvoice->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 font-semibold">₦{{ number_format($childInvoice->total, 2) }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('invoices.show', $childInvoice->id) }}" class="text-blue-600 hover:underline text-sm">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Notification History -->
    @if($invoice->is_recurring && $invoice->notifications && $invoice->notifications->count() > 0)
    <div class="bg-white p-6 rounded-lg shadow-md mt-6">
        <h2 class="text-xl font-semibold mb-4">Notification History</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Type</th>
                        <th class="px-4 py-2 text-left">Sent To</th>
                        <th class="px-4 py-2 text-left">Scheduled For</th>
                        <th class="px-4 py-2 text-left">Sent At</th>
                        <th class="px-4 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->notifications->sortByDesc('created_at') as $notification)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs {{ $notification->notification_type == 'client_reminder' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ str_replace('_', ' ', ucfirst($notification->notification_type)) }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $notification->sent_to }}</td>
                        <td class="px-4 py-2">{{ $notification->scheduled_for_date->format('M d, Y') }}</td>
                        <td class="px-4 py-2">{{ $notification->sent_at ? $notification->sent_at->format('M d, Y H:i') : 'N/A' }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs {{ $notification->status == 'sent' ? 'bg-green-100 text-green-800' : ($notification->status == 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($notification->status) }}
                            </span>
                            @if($notification->error_message)
                                <div class="text-xs text-red-600 mt-1">{{ Str::limit($notification->error_message, 50) }}</div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection


