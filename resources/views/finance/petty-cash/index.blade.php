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

    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <h3 class="font-semibold text-blue-800 mb-2">Petty Cash Description</h3>
        <p class="text-sm text-blue-700">
            Tracks small, day-to-day cash expenses that don't require formal invoices. Used for minor purchases like office supplies, refreshments, or small repairs. Helps maintain accountability for small cash disbursements and ensures proper documentation of all cash expenses.
        </p>
    </div>

    <h2 class="text-2xl font-semibold mb-4">Petty Cash Entries</h2>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
            <h3 class="text-sm font-medium text-red-700">Total Expenses (Authorized)</h3>
            <p class="text-2xl font-bold text-red-600">₦{{ number_format($totalExpenses, 2) }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
            <h3 class="text-sm font-medium text-green-700">Total Replenishments (Authorized)</h3>
            <p class="text-2xl font-bold text-green-600">₦{{ number_format($totalReplenishments, 2) }}</p>
        </div>
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <h3 class="text-sm font-medium text-blue-700">Current Balance</h3>
            <p class="text-2xl font-bold text-blue-600">₦{{ number_format($currentBalance, 2) }}</p>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <h3 class="text-sm font-medium text-yellow-700">Pending Authorization</h3>
            <p class="text-2xl font-bold text-yellow-600">{{ $pendingCount ?? 0 }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
            <h3 class="text-sm font-medium text-green-700">Authorized</h3>
            <p class="text-2xl font-bold text-green-600">{{ $authorizedCount ?? 0 }}</p>
        </div>
        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
            <h3 class="text-sm font-medium text-red-700">Unauthorized</h3>
            <p class="text-2xl font-bold text-red-600">{{ $unauthorizedCount ?? 0 }}</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="flex flex-wrap justify-between mb-6 gap-4">
        <form action="{{ route('petty-cash.index') }}" method="GET" class="flex gap-2 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            <select name="type" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">All Types</option>
                <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                <option value="replenishment" {{ request('type') == 'replenishment' ? 'selected' : '' }}>Replenishment</option>
            </select>
            <select name="authorization_status" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">All Authorization Status</option>
                <option value="pending" {{ request('authorization_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="authorized" {{ request('authorization_status') == 'authorized' ? 'selected' : '' }}>Authorized</option>
                <option value="unauthorized" {{ request('authorization_status') == 'unauthorized' ? 'selected' : '' }}>Unauthorized</option>
            </select>
            <select name="employee_filter" id="employee_filter" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="all" {{ request('employee_filter') == 'all' || !request('employee_filter') ? 'selected' : '' }}>All Entries</option>
                <option value="with_employee" {{ request('employee_filter') == 'with_employee' ? 'selected' : '' }}>With Employee</option>
                <option value="without_employee" {{ request('employee_filter') == 'without_employee' ? 'selected' : '' }}>Without Employee</option>
                <option value="specific_employee" {{ request('employee_id') ? 'selected' : '' }}>Specific Employee</option>
            </select>
            <select name="employee_id" id="employee_id_select" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500" style="{{ !request('employee_id') ? 'display: none;' : '' }}">
                <option value="">-- Select Employee --</option>
                @foreach($employees ?? [] as $employee)
                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            <button type="submit" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">Search</button>
            <a href="{{ route('petty-cash.index') }}" class="bg-gray-500 text-white px-4 py-3 rounded-md shadow-md hover:bg-gray-600 transition">Clear</a>
        </form>
        <div class="flex gap-2">
            <a href="{{ route('petty-cash.export', request()->query()) }}" class="bg-green-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-green-700 transition">Export CSV</a>
            <a href="{{ route('petty-cash.create') }}" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">Add Entry</a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead class="bg-red-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Date</th>
                    <th class="px-6 py-4 text-left font-semibold">Type</th>
                    <th class="px-6 py-4 text-left font-semibold">Description</th>
                    <th class="px-6 py-4 text-left font-semibold">Receiver/Beneficiary</th>
                    <th class="px-6 py-4 text-left font-semibold">Employee</th>
                    <th class="px-6 py-4 text-left font-semibold">Category</th>
                    <th class="px-6 py-4 text-left font-semibold">Amount</th>
                    <th class="px-6 py-4 text-left font-semibold">Receipt #</th>
                    <th class="px-6 py-4 text-left font-semibold">Authorization</th>
                    <th class="px-6 py-4 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($entries as $entry)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $entry->entry_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs {{ $entry->transaction_type === 'expense' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $entry->transaction_type_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $entry->description }}</td>
                    <td class="px-6 py-4">{{ $entry->receiver_beneficiary ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        @if($entry->employee)
                            <a href="{{ route('petty-cash.employee', $entry->employee_id) }}" class="text-blue-600 hover:underline font-medium">
                                {{ $entry->employee->name }}
                            </a>
                            <p class="text-xs text-gray-500">(For salary deduction)</p>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $entry->category ?? 'N/A' }}</td>
                    <td class="px-6 py-4 font-semibold">₦{{ number_format($entry->amount, 2) }}</td>
                    <td class="px-6 py-4">{{ $entry->receipt_number ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusColor = match($entry->authorization_status ?? 'pending') {
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'authorized' => 'bg-green-100 text-green-800',
                                'unauthorized' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800',
                            };
                        @endphp
                        <span class="px-2 py-1 rounded text-xs {{ $statusColor }}">
                            {{ $entry->authorization_status_label ?? 'Pending' }}
                        </span>
                        @if($entry->authorized_at)
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $entry->authorized_at->format('M d, Y') }}
                            </p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            <a href="{{ route('petty-cash.show', $entry->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">View</a>
                            <a href="{{ route('petty-cash.edit', $entry->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 transition">Edit</a>
                            @if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')))
                                @if(($entry->authorization_status ?? 'pending') === 'pending')
                                <button onclick="showAuthorizationModal({{ $entry->id }})" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition">Authorize</button>
                                <button onclick="showUnauthorizationModal({{ $entry->id }})" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition">Unauthorize</button>
                                @endif
                            @endif
                            @if(auth()->user()->hasRole('super-admin'))
                            <form action="{{ route('petty-cash.destroy', $entry->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition w-full" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-4 text-center text-gray-500">No entries found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $entries->appends(request()->query())->links('pagination::tailwind') }}
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

// Show/hide employee dropdown based on filter selection
document.addEventListener('DOMContentLoaded', function() {
    const employeeFilter = document.getElementById('employee_filter');
    const employeeSelect = document.getElementById('employee_id_select');
    
    if (employeeFilter && employeeSelect) {
        // Set initial state
        if (employeeFilter.value === 'specific_employee') {
            employeeSelect.style.display = 'block';
        }
        
        employeeFilter.addEventListener('change', function() {
            if (this.value === 'specific_employee') {
                employeeSelect.style.display = 'block';
                employeeSelect.required = true;
            } else {
                employeeSelect.style.display = 'none';
                employeeSelect.value = '';
                employeeSelect.required = false;
            }
        });
    }
});
</script>
@endsection

