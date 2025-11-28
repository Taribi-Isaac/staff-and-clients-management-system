@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Create New Invoice/Receipt/Quote</h1>

    <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm" class="bg-white p-8 rounded-lg shadow-md">
        @csrf

        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                <select name="type" id="type" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="invoice" {{ old('type') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                    <option value="receipt" {{ old('type') == 'receipt' ? 'selected' : '' }}>Receipt</option>
                    <option value="quote" {{ old('type') == 'quote' ? 'selected' : '' }}>Quote</option>
                </select>
                @error('type')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="e.g., Monthly Service Invoice">
                @error('title')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="invoice_date" class="block text-sm font-medium text-gray-700 mb-2">Invoice Date *</label>
                <input type="date" name="invoice_date" id="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('invoice_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('due_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" id="status" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ old('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Client Information -->
        <div class="mb-6 border-t pt-6">
            <h2 class="text-xl font-semibold mb-4">Client Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Select Client (Optional)</label>
                    <select name="client_id" id="client_id" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">-- Select Client --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" data-name="{{ $client->client_name }}" data-email="{{ $client->email }}" data-address="{{ $client->service_address ?? '' }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->client_name }} ({{ $client->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">Client Name</label>
                    <input type="text" name="client_name" id="client_name" value="{{ old('client_name') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('client_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="client_email" class="block text-sm font-medium text-gray-700 mb-2">Client Email</label>
                    <input type="email" name="client_email" id="client_email" value="{{ old('client_email') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('client_email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="client_address" class="block text-sm font-medium text-gray-700 mb-2">Client Address</label>
                    <textarea name="client_address" id="client_address" rows="2" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('client_address') }}</textarea>
                    @error('client_address')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="mb-6 border-t pt-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Invoice Items</h2>
                <button type="button" id="addItem" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">Add Item</button>
            </div>
            <div id="itemsContainer">
                <div class="item-row mb-4 p-4 border border-gray-200 rounded-lg">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                            <input type="text" name="items[0][description]" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Item description">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                            <input type="number" name="items[0][quantity]" step="0.01" min="0.01" value="1" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500 item-quantity">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price *</label>
                            <input type="number" name="items[0][unit_price]" step="0.01" min="0" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500 item-price" placeholder="0.00">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                            <input type="text" class="w-full p-2 border border-gray-300 rounded bg-gray-100 item-total" readonly value="0.00">
                        </div>
                        <div class="col-span-1 flex items-end">
                            <button type="button" class="remove-item bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition">Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Totals -->
        <div class="mb-6 border-t pt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%)</label>
                    <input type="number" name="tax_rate" id="tax_rate" step="0.01" min="0" max="100" value="{{ old('tax_rate', 0) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('tax_rate')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="discount" class="block text-sm font-medium text-gray-700 mb-2">Discount (₦)</label>
                    <input type="number" name="discount" id="discount" step="0.01" min="0" value="{{ old('discount', 0) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('discount')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <div class="flex justify-between mb-2">
                    <span class="font-medium">Subtotal:</span>
                    <span id="subtotal" class="font-semibold">₦0.00</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="font-medium">Tax:</span>
                    <span id="taxAmount" class="font-semibold">₦0.00</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="font-medium">Discount:</span>
                    <span id="discountDisplay" class="font-semibold">₦0.00</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-gray-300">
                    <span class="text-lg font-bold">Total:</span>
                    <span id="total" class="text-lg font-bold text-red-600">₦0.00</span>
                </div>
            </div>
        </div>

        <!-- Notes and Terms -->
        <div class="mb-6 border-t pt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="terms" class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                    <textarea name="terms" id="terms" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Payment terms and conditions...">{{ old('terms') }}</textarea>
                    @error('terms')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 text-center">
            <button type="submit" class="bg-red-600 text-white px-8 py-3 rounded-md shadow-md hover:bg-red-700 transition text-lg font-semibold">Create Invoice</button>
            <a href="{{ route('invoices.index') }}" class="ml-4 bg-gray-500 text-white px-8 py-3 rounded-md shadow-md hover:bg-gray-600 transition text-lg font-semibold">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;

    // Auto-fill client details when client is selected
    document.getElementById('client_id').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.value) {
            document.getElementById('client_name').value = option.dataset.name || '';
            document.getElementById('client_email').value = option.dataset.email || '';
            document.getElementById('client_address').value = option.dataset.address || '';
        }
    });

    // Add new item
    document.getElementById('addItem').addEventListener('click', function() {
        const container = document.getElementById('itemsContainer');
        const newItem = document.createElement('div');
        newItem.className = 'item-row mb-4 p-4 border border-gray-200 rounded-lg';
        newItem.innerHTML = `
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <input type="text" name="items[${itemIndex}][description]" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Item description">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                    <input type="number" name="items[${itemIndex}][quantity]" step="0.01" min="0.01" value="1" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500 item-quantity">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price *</label>
                    <input type="number" name="items[${itemIndex}][unit_price]" step="0.01" min="0" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500 item-price" placeholder="0.00">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded bg-gray-100 item-total" readonly value="0.00">
                </div>
                <div class="col-span-1 flex items-end">
                    <button type="button" class="remove-item bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition">Remove</button>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        itemIndex++;
        attachItemListeners(newItem);
    });

    // Remove item
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            const itemRow = e.target.closest('.item-row');
            if (document.querySelectorAll('.item-row').length > 1) {
                itemRow.remove();
                calculateTotals();
            } else {
                alert('At least one item is required');
            }
        }
    });

    // Calculate item totals and grand total
    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const total = quantity * price;
            row.querySelector('.item-total').value = total.toFixed(2);
            subtotal += total;
        });

        const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const taxAmount = (subtotal - discount) * (taxRate / 100);
        const total = subtotal - discount + taxAmount;

        document.getElementById('subtotal').textContent = '₦' + subtotal.toFixed(2);
        document.getElementById('taxAmount').textContent = '₦' + taxAmount.toFixed(2);
        document.getElementById('discountDisplay').textContent = '₦' + discount.toFixed(2);
        document.getElementById('total').textContent = '₦' + total.toFixed(2);
    }

    function attachItemListeners(itemRow) {
        const quantityInput = itemRow.querySelector('.item-quantity');
        const priceInput = itemRow.querySelector('.item-price');
        
        [quantityInput, priceInput].forEach(input => {
            input.addEventListener('input', calculateTotals);
        });
    }

    // Attach listeners to existing items
    document.querySelectorAll('.item-row').forEach(itemRow => {
        attachItemListeners(itemRow);
    });

    // Attach listeners to tax and discount
    document.getElementById('tax_rate').addEventListener('input', calculateTotals);
    document.getElementById('discount').addEventListener('input', calculateTotals);

    // Initial calculation
    calculateTotals();
});
</script>
@endsection


