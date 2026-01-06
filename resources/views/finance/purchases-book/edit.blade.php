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

    <h2 class="text-2xl font-semibold mb-4">Edit Purchases Book Entry</h2>

    <form action="{{ route('purchases-book.update', $entry->id) }}" method="POST" class="bg-white p-8 rounded-lg shadow-md">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="entry_date" class="block text-sm font-medium text-gray-700 mb-2">Entry Date *</label>
                <input type="date" name="entry_date" id="entry_date" value="{{ old('entry_date', $entry->entry_date->format('Y-m-d')) }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('entry_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">Supplier (Optional)</label>
                <select name="supplier_id" id="supplier_id" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $entry->supplier_id) == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->supplier_name }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="supplier_name" class="block text-sm font-medium text-gray-700 mb-2">Supplier Name *</label>
                <input type="text" name="supplier_name" id="supplier_name" value="{{ old('supplier_name', $entry->supplier_name) }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('supplier_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="invoice_number" class="block text-sm font-medium text-gray-700 mb-2">Invoice Number</label>
                <input type="text" name="invoice_number" id="invoice_number" value="{{ old('invoice_number', $entry->invoice_number) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('invoice_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount (Before Tax) *</label>
                <input type="number" name="amount" id="amount" value="{{ old('amount', $entry->amount) }}" step="0.01" min="0" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('amount')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="tax_amount" class="block text-sm font-medium text-gray-700 mb-2">Tax Amount</label>
                <input type="number" name="tax_amount" id="tax_amount" value="{{ old('tax_amount', $entry->tax_amount) }}" step="0.01" min="0" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('tax_amount')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="discount" class="block text-sm font-medium text-gray-700 mb-2">Discount</label>
                <input type="number" name="discount" id="discount" value="{{ old('discount', $entry->discount) }}" step="0.01" min="0" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('discount')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="total" class="block text-sm font-medium text-gray-700 mb-2">Total Amount *</label>
                <input type="number" name="total" id="total" value="{{ old('total', $entry->total) }}" step="0.01" min="0" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('total')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">Payment Status *</label>
                <select name="payment_status" id="payment_status" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="pending" {{ old('payment_status', $entry->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="partial" {{ old('payment_status', $entry->payment_status) == 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="paid" {{ old('payment_status', $entry->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
                @error('payment_status')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="paid_amount" class="block text-sm font-medium text-gray-700 mb-2">Paid Amount</label>
                <input type="number" name="paid_amount" id="paid_amount" value="{{ old('paid_amount', $entry->paid_amount) }}" step="0.01" min="0" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('paid_amount')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <input type="text" name="payment_method" id="payment_method" value="{{ old('payment_method', $entry->payment_method) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('payment_method')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('description', $entry->description) }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" id="notes" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('notes', $entry->notes) }}</textarea>
                @error('notes')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('purchases-book.show', $entry->id) }}" class="bg-gray-500 text-white px-6 py-3 rounded-md shadow-md hover:bg-gray-600 transition">Cancel</a>
            <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-md shadow-md hover:bg-red-700 transition">Update Entry</button>
        </div>
    </form>

    <script>
        // Auto-calculate total
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.getElementById('amount');
            const taxInput = document.getElementById('tax_amount');
            const discountInput = document.getElementById('discount');
            const totalInput = document.getElementById('total');

            function calculateTotal() {
                const amount = parseFloat(amountInput.value) || 0;
                const tax = parseFloat(taxInput.value) || 0;
                const discount = parseFloat(discountInput.value) || 0;
                const total = amount + tax - discount;
                totalInput.value = total.toFixed(2);
            }

            amountInput.addEventListener('input', calculateTotal);
            taxInput.addEventListener('input', calculateTotal);
            discountInput.addEventListener('input', calculateTotal);
        });
    </script>
</div>
@endsection

