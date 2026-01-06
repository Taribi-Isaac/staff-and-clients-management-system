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

    <h2 class="text-2xl font-semibold mb-4">Petty Cash Entry Details</h2>

    <div class="bg-white p-8 rounded-lg shadow-md mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Entry Date</label>
                <p class="text-lg">{{ $entry->entry_date->format('M d, Y') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Type</label>
                <p class="text-lg">
                    <span class="px-2 py-1 rounded text-xs {{ $entry->transaction_type === 'expense' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ $entry->transaction_type_label }}
                    </span>
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                <p class="text-lg font-semibold">₦{{ number_format($entry->amount, 2) }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Receiver/Beneficiary</label>
                <p class="text-lg">{{ $entry->receiver_beneficiary ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <p class="text-lg">{{ $entry->category ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <p class="text-lg">{{ $entry->description }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Receipt Number</label>
                <p class="text-lg">{{ $entry->receipt_number ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Approved By</label>
                <p class="text-lg">{{ $entry->approved_by ?? ($entry->approver->name ?? 'N/A') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Created By</label>
                <p class="text-lg">{{ $entry->creator->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Authorization Status</label>
                <p class="text-lg">
                    @php
                        $statusColor = match($entry->authorization_status ?? 'pending') {
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'authorized' => 'bg-green-100 text-green-800',
                            'unauthorized' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <span class="px-3 py-1 rounded text-sm font-semibold {{ $statusColor }}">
                        {{ $entry->authorization_status_label ?? 'Pending' }}
                    </span>
                </p>
            </div>
            @if($entry->authorized_at)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Authorized By</label>
                <p class="text-lg">{{ $entry->authorizer->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Authorized At</label>
                <p class="text-lg">{{ $entry->authorized_at->format('M d, Y H:i') }}</p>
            </div>
            @endif
            @if($entry->authorization_notes)
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Authorization Notes</label>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $entry->authorization_notes }}</p>
            </div>
            @endif
        </div>

        @if($entry->notes)
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $entry->notes }}</p>
        </div>
        @endif
    </div>

    <div class="flex gap-4">
        @if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')))
            @if(($entry->authorization_status ?? 'pending') === 'pending')
            <button onclick="showAuthorizationModal({{ $entry->id }})" class="bg-green-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-green-600 transition">Authorize</button>
            <button onclick="showUnauthorizationModal({{ $entry->id }})" class="bg-red-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-600 transition">Unauthorize</button>
            @endif
        @endif
        <a href="{{ route('petty-cash.edit', $entry->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-yellow-600 transition">Edit Entry</a>
        <a href="{{ route('petty-cash.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-gray-600 transition">Back to Petty Cash</a>
    </div>
</div>

<!-- Authorization Modal -->
<div id="authorizationModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Authorize Entry</h3>
            <form id="authorizationForm" method="POST">
                @csrf
                <input type="hidden" name="authorization_status" id="authStatus" value="authorized">
                <div class="mb-4">
                    <label for="authorization_notes" class="block text-sm font-medium text-gray-700 mb-2">Authorization Notes (Optional)</label>
                    <textarea name="authorization_notes" id="authorization_notes" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Add any notes about this authorization..."></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeAuthorizationModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">Cancel</button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition" id="submitBtn">Authorize</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showAuthorizationModal(entryId) {
    document.getElementById('authorizationForm').action = '/petty-cash/' + entryId + '/authorize';
    document.getElementById('authStatus').value = 'authorized';
    document.getElementById('modalTitle').textContent = 'Authorize Entry';
    document.getElementById('submitBtn').textContent = 'Authorize';
    document.getElementById('submitBtn').className = 'bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition';
    document.getElementById('authorizationModal').classList.remove('hidden');
}

function showUnauthorizationModal(entryId) {
    document.getElementById('authorizationForm').action = '/petty-cash/' + entryId + '/authorize';
    document.getElementById('authStatus').value = 'unauthorized';
    document.getElementById('modalTitle').textContent = 'Unauthorize Entry';
    document.getElementById('submitBtn').textContent = 'Unauthorize';
    document.getElementById('submitBtn').className = 'bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition';
    document.getElementById('authorizationModal').classList.remove('hidden');
}

function closeAuthorizationModal() {
    document.getElementById('authorizationModal').classList.add('hidden');
    document.getElementById('authorization_notes').value = '';
}
</script>
@endsection
</div>
@endsection

