@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Finance Management</h1>

    <!-- Navigation Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="finance-tabs" role="tablist">
            <li class="me-2" role="presentation">
                <a href="{{ route('cash-book.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('cash-book.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">Cash Book</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('petty-cash.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('petty-cash.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">Petty Cash</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('sales-book.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('sales-book.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">Sales Book</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('purchases-book.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('purchases-book.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">Purchases Book</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('ar-ledger.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('ar-ledger.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">AR Ledger</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('ap-ledger.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('ap-ledger.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">AP Ledger</a>
            </li>
            <li class="me-2" role="presentation">
                <a href="{{ route('payroll-book.index') }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ request()->routeIs('payroll-book.*') ? 'text-red-600 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">Payroll Book</a>
            </li>
        </ul>
    </div>

    <h2 class="text-2xl font-semibold mb-4">Add Cash Book Entry</h2>

    <form action="{{ route('cash-book.store') }}" method="POST" class="bg-white p-8 rounded-lg shadow-md">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="entry_date" class="block text-sm font-medium text-gray-700 mb-2">Entry Date *</label>
                <input type="date" name="entry_date" id="entry_date" value="{{ old('entry_date', date('Y-m-d')) }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('entry_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="transaction_type" class="block text-sm font-medium text-gray-700 mb-2">Transaction Type *</label>
                <select name="transaction_type" id="transaction_type" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select Type --</option>
                    <option value="receipt" {{ old('transaction_type') == 'receipt' ? 'selected' : '' }}>Receipt (Money In)</option>
                    <option value="payment" {{ old('transaction_type') == 'payment' ? 'selected' : '' }}>Payment (Money Out)</option>
                </select>
                @error('transaction_type')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0.01" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('amount')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="reference" class="block text-sm font-medium text-gray-700 mb-2">Reference Number</label>
                <input type="text" name="reference" id="reference" value="{{ old('reference') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Receipt number, check number, etc.">
                @error('reference')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="related_invoice_id" class="block text-sm font-medium text-gray-700 mb-2">Related Invoice (Optional)</label>
                <select name="related_invoice_id" id="related_invoice_id" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select Invoice --</option>
                    @foreach($invoices as $invoice)
                        <option value="{{ $invoice->id }}" {{ old('related_invoice_id') == $invoice->id ? 'selected' : '' }}>
                            {{ $invoice->invoice_number }} - {{ $invoice->client_name }} (₦{{ number_format($invoice->total, 2) }})
                        </option>
                    @endforeach
                </select>
                @error('related_invoice_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="related_client_id" class="block text-sm font-medium text-gray-700 mb-2">Related Client (Optional)</label>
                <select name="related_client_id" id="related_client_id" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select Client --</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('related_client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->client_name }}
                        </option>
                    @endforeach
                </select>
                @error('related_client_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <input type="text" name="description" id="description" value="{{ old('description') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Brief description of the transaction">
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea name="notes" id="notes" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('cash-book.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-md shadow-md hover:bg-gray-600 transition">Cancel</a>
            <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-md shadow-md hover:bg-red-700 transition">Create Entry</button>
        </div>
    </form>
</div>
@endsection





