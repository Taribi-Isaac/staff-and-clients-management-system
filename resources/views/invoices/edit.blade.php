@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Edit Invoice/Receipt/Quote</h1>

    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST" id="invoiceForm" class="bg-white p-8 rounded-lg shadow-md">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                <select name="type" id="type" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="invoice" {{ $invoice->type == 'invoice' ? 'selected' : '' }}>Invoice</option>
                    <option value="receipt" {{ $invoice->type == 'receipt' ? 'selected' : '' }}>Receipt</option>
                    <option value="quote" {{ $invoice->type == 'quote' ? 'selected' : '' }}>Quote</option>
                </select>
                @error('type')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $invoice->title) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="e.g., Monthly Service Invoice">
                @error('title')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="invoice_date" class="block text-sm font-medium text-gray-700 mb-2">Invoice Date *</label>
                <input type="date" name="invoice_date" id="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('invoice_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('due_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" id="status" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="draft" {{ $invoice->status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ $invoice->status == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="overdue" {{ $invoice->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="cancelled" {{ $invoice->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                    <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">Client Name *</label>
                    <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $invoice->client_name) }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('client_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="client_email" class="block text-sm font-medium text-gray-700 mb-2">Client Email</label>
                    <input type="email" name="client_email" id="client_email" value="{{ old('client_email', $invoice->client_email) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('client_email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="client_address" class="block text-sm font-medium text-gray-700 mb-2">Client Address</label>
                    <textarea name="client_address" id="client_address" rows="2" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('client_address', $invoice->client_address) }}</textarea>
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
                @foreach($invoice->items as $index => $item)
                <div class="item-row mb-4 p-4 border border-gray-200 rounded-lg">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                            <input type="text" name="items[{{ $index }}][description]" value="{{ old("items.$index.description", $item->description) }}" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Item description">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                            <input type="number" name="items[{{ $index }}][quantity]" step="0.01" min="0.01" value="{{ old("items.$index.quantity", $item->quantity) }}" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500 item-quantity">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price *</label>
                            <input type="number" name="items[{{ $index }}][unit_price]" step="0.01" min="0" value="{{ old("items.$index.unit_price", $item->unit_price) }}" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500 item-price" placeholder="0.00">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                            <input type="text" class="w-full p-2 border border-gray-300 rounded bg-gray-100 item-total" readonly value="{{ number_format($item->total, 2) }}">
                        </div>
                        <div class="col-span-1 flex items-end">
                            <button type="button" class="remove-item bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition">Remove</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Totals -->
        <div class="mb-6 border-t pt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%)</label>
                    <input type="number" name="tax_rate" id="tax_rate" step="0.01" min="0" max="100" value="{{ old('tax_rate', $invoice->tax_rate) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('tax_rate')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="discount" class="block text-sm font-medium text-gray-700 mb-2">Discount (₦)</label>
                    <input type="number" name="discount" id="discount" step="0.01" min="0" value="{{ old('discount', $invoice->discount) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('discount')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <div class="flex justify-between mb-2">
                    <span class="font-medium">Subtotal:</span>
                    <span id="subtotal" class="font-semibold">₦{{ number_format($invoice->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="font-medium">Tax:</span>
                    <span id="taxAmount" class="font-semibold">₦{{ number_format($invoice->tax_amount, 2) }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="font-medium">Discount:</span>
                    <span id="discountDisplay" class="font-semibold">₦{{ number_format($invoice->discount, 2) }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-gray-300">
                    <span class="text-lg font-bold">Total:</span>
                    <span id="total" class="text-lg font-bold text-red-600">₦{{ number_format($invoice->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Notes and Terms -->
        <div class="mb-6 border-t pt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Additional notes...">{{ old('notes', $invoice->notes) }}</textarea>
                    @error('notes')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="terms" class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                    <textarea name="terms" id="terms" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Payment terms and conditions...">{{ old('terms', $invoice->terms) }}</textarea>
                    @error('terms')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Recurring Invoice Settings -->
        <div class="mb-6 border-t pt-6">
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_recurring" id="is_recurring" value="1" {{ old('is_recurring', $invoice->is_recurring) ? 'checked' : '' }} class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">Make this a recurring invoice</span>
                </label>
            </div>

            <div id="recurringSettings" class="{{ old('is_recurring', $invoice->is_recurring) ? '' : 'hidden' }} grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="recurring_frequency" class="block text-sm font-medium text-gray-700 mb-2">Recurring Frequency *</label>
                    <select name="recurring_frequency" id="recurring_frequency" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">Select frequency</option>
                        <option value="weekly" {{ old('recurring_frequency', $invoice->recurring_frequency) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="biweekly" {{ old('recurring_frequency', $invoice->recurring_frequency) == 'biweekly' ? 'selected' : '' }}>Bi-weekly</option>
                        <option value="monthly" {{ old('recurring_frequency', $invoice->recurring_frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="quarterly" {{ old('recurring_frequency', $invoice->recurring_frequency) == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                        <option value="yearly" {{ old('recurring_frequency', $invoice->recurring_frequency) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                    @error('recurring_frequency')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="notification_days_before" class="block text-sm font-medium text-gray-700 mb-2">Notification Days Before Due Date</label>
                    <input type="number" name="notification_days_before" id="notification_days_before" value="{{ old('notification_days_before', $invoice->notification_days_before ?? 3) }}" min="0" max="30" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <p class="text-xs text-gray-500 mt-1">Number of days before due date to send reminder (default: 3)</p>
                    @error('notification_days_before')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="recurring_start_date" class="block text-sm font-medium text-gray-700 mb-2">Recurring Start Date</label>
                    <input type="date" name="recurring_start_date" id="recurring_start_date" value="{{ old('recurring_start_date', $invoice->recurring_start_date?->format('Y-m-d')) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to use invoice date</p>
                    @error('recurring_start_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="recurring_end_date" class="block text-sm font-medium text-gray-700 mb-2">Recurring End Date (Optional)</label>
                    <input type="date" name="recurring_end_date" id="recurring_end_date" value="{{ old('recurring_end_date', $invoice->recurring_end_date?->format('Y-m-d')) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <p class="text-xs text-gray-500 mt-1">Leave empty for no end date</p>
                    @error('recurring_end_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 text-center">
            <button type="submit" class="bg-red-600 text-white px-8 py-3 rounded-md shadow-md hover:bg-red-700 transition text-lg font-semibold">Update Invoice</button>
            <a href="{{ route('invoices.show', $invoice->id) }}" class="ml-4 bg-gray-500 text-white px-8 py-3 rounded-md shadow-md hover:bg-gray-600 transition text-lg font-semibold">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = {{ count($invoice->items) }};

    // Auto-fill client details when client is selected (if client_id field exists)
    const clientIdField = document.getElementById('client_id');
    if (clientIdField) {
        clientIdField.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (option.value) {
                document.getElementById('client_name').value = option.dataset.name || '';
                document.getElementById('client_email').value = option.dataset.email || '';
                document.getElementById('client_address').value = option.dataset.address || '';
            }
        });
    }

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

    // Toggle recurring settings visibility
    const isRecurringCheckbox = document.getElementById('is_recurring');
    const recurringSettings = document.getElementById('recurringSettings');
    
    function toggleRecurringSettings() {
        if (isRecurringCheckbox.checked) {
            recurringSettings.classList.remove('hidden');
            document.getElementById('recurring_frequency').required = true;
        } else {
            recurringSettings.classList.add('hidden');
            document.getElementById('recurring_frequency').required = false;
        }
    }
    
    isRecurringCheckbox.addEventListener('change', toggleRecurringSettings);
    
    // Initialize on page load
    toggleRecurringSettings();
});
</script>
@endsection


