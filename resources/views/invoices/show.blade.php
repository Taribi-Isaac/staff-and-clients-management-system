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
</div>
@endsection


