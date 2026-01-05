@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Create Inventory Transaction</h1>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('inventory-transactions.store') }}" method="POST" class="bg-white p-8 rounded-lg shadow-md">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Item Selection -->
            <div>
                <label for="item_id" class="block text-sm font-medium text-gray-700 mb-2">Item *</label>
                <select name="item_id" id="item_id" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select Item --</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" 
                            {{ old('item_id', request('item_id')) == $item->id ? 'selected' : '' }}
                            data-quantity="{{ $item->quantity }}"
                            data-unit="{{ $item->unit }}">
                            {{ $item->item_name }} ({{ $item->quantity }} {{ $item->unit }} available)
                        </option>
                    @endforeach
                </select>
                @error('item_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Transaction Type -->
            <div>
                <label for="transaction_type" class="block text-sm font-medium text-gray-700 mb-2">Transaction Type *</label>
                <select name="transaction_type" id="transaction_type" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select Type --</option>
                    <option value="purchase" {{ old('transaction_type') == 'purchase' ? 'selected' : '' }}>Purchase (Add Stock)</option>
                    <option value="assignment" {{ old('transaction_type') == 'assignment' ? 'selected' : '' }}>Assignment (Assign to Staff/Client/Project)</option>
                    <option value="return" {{ old('transaction_type') == 'return' ? 'selected' : '' }}>Return (Return to Stock)</option>
                    <option value="consumption" {{ old('transaction_type') == 'consumption' ? 'selected' : '' }}>Consumption (Use/Consume)</option>
                    <option value="adjustment" {{ old('transaction_type') == 'adjustment' ? 'selected' : '' }}>Adjustment (Stock Correction)</option>
                </select>
                @error('transaction_type')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Quantity -->
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" min="1" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                <p class="mt-1 text-xs text-gray-500" id="available-stock"></p>
                @error('quantity')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Transaction Date -->
            <div>
                <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-2">Transaction Date *</label>
                <input type="date" name="transaction_date" id="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('transaction_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Assignment Section (shown only for assignment type) -->
            <div id="assignment-section" class="md:col-span-2 hidden">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">Assignment Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <!-- Assign to Staff -->
                    <div>
                        <label for="assigned_to_user_id" class="block text-sm font-medium text-gray-700 mb-2">Assign to Staff</label>
                        <select name="assigned_to_user_id" id="assigned_to_user_id" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">-- Select Staff --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to_user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to_user_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Assign to Client -->
                    <div>
                        <label for="assigned_to_client_id" class="block text-sm font-medium text-gray-700 mb-2">Assign to Client</label>
                        <select name="assigned_to_client_id" id="assigned_to_client_id" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">-- Select Client --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('assigned_to_client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->client_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to_client_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Assign to Project -->
                    <div>
                        <label for="assigned_to_project_id" class="block text-sm font-medium text-gray-700 mb-2">Assign to Project</label>
                        <select name="assigned_to_project_id" id="assigned_to_project_id" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">-- Select Project --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('assigned_to_project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to_project_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Expected Return Date (for assignments) -->
                <div>
                    <label for="expected_return_date" class="block text-sm font-medium text-gray-700 mb-2">Expected Return Date</label>
                    <input type="date" name="expected_return_date" id="expected_return_date" value="{{ old('expected_return_date') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <p class="mt-1 text-xs text-gray-500">When is this item expected to be returned?</p>
                    @error('expected_return_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="md:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" id="notes" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Additional notes about this transaction...">{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('inventory-transactions.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-md shadow-md hover:bg-gray-600 transition">Cancel</a>
            <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-md shadow-md hover:bg-red-700 transition">Create Transaction</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const transactionTypeSelect = document.getElementById('transaction_type');
    const assignmentSection = document.getElementById('assignment-section');
    const itemSelect = document.getElementById('item_id');
    const availableStock = document.getElementById('available-stock');
    const quantityInput = document.getElementById('quantity');
    const expectedReturnDateInput = document.getElementById('expected_return_date');
    const transactionDateInput = document.getElementById('transaction_date');

    // Show/hide assignment section based on transaction type
    function toggleAssignmentSection() {
        if (transactionTypeSelect.value === 'assignment') {
            assignmentSection.classList.remove('hidden');
        } else {
            assignmentSection.classList.add('hidden');
            // Clear assignment fields when hidden
            document.getElementById('assigned_to_user_id').value = '';
            document.getElementById('assigned_to_client_id').value = '';
            document.getElementById('assigned_to_project_id').value = '';
            expectedReturnDateInput.value = '';
        }
    }

    // Update available stock display
    function updateAvailableStock() {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const quantity = selectedOption.getAttribute('data-quantity');
            const unit = selectedOption.getAttribute('data-unit');
            availableStock.textContent = `Available stock: ${quantity} ${unit}`;
        } else {
            availableStock.textContent = '';
        }
    }

    // Set minimum date for expected return date
    transactionDateInput.addEventListener('change', function() {
        expectedReturnDateInput.min = this.value;
    });

    // Initial setup
    toggleAssignmentSection();
    updateAvailableStock();

    // Event listeners
    transactionTypeSelect.addEventListener('change', toggleAssignmentSection);
    itemSelect.addEventListener('change', updateAvailableStock);
});
</script>
@endsection

