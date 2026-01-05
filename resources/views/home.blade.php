@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8 px-6">
        <h1 class="text-3xl font-bold mb-8 text-center">Dashboard Overview</h1>

        <!-- Summary Cards Row 1: People -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">People</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('clients.index') }}" class="block bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Total Clients</h3>
                            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $allClients }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activeClients }} active, {{ $inactiveClients }} inactive</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('employees.index') }}" class="block bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Employees</h3>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ $allEmployees }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activeEmployees }} active</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                @if(auth()->user()->hasRole('super-admin'))
                <a href="{{ route('staff.index') }}" class="block bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Staff</h3>
                            <p class="text-3xl font-bold text-purple-600 mt-2">{{ $allStaff }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activeStaff }} active</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </a>
                @endif

                <a href="{{ route('issues.index') }}" class="block bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Issues</h3>
                            <p class="text-3xl font-bold text-orange-600 mt-2">{{ $allIssues }}</p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Summary Cards Row 2: Projects & Invoices -->
        @if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')))
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Projects & Finance</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('projects.index') }}" class="block bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Projects</h3>
                            <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $allProjects }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activeProjects }} active, {{ $completedProjects }} completed</p>
                        </div>
                        <div class="bg-indigo-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Project Budget</h3>
                            <p class="text-3xl font-bold text-indigo-600 mt-2">₦{{ number_format($totalProjectBudget, 2) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Total allocated</p>
                        </div>
                        <div class="bg-indigo-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <a href="{{ route('invoices.index') }}" class="block bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Invoices & Receipts</h3>
                            <p class="text-3xl font-bold text-red-600 mt-2">{{ $allInvoices }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $totalInvoices }} invoices, {{ $totalReceipts }} receipts, {{ $totalQuotes }} quotes</p>
                        </div>
                        <div class="bg-red-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Total Revenue</h3>
                            <p class="text-3xl font-bold text-green-600 mt-2">₦{{ number_format($totalRevenue, 2) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $paidInvoices }} paid, {{ $pendingInvoices }} pending</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards Row 3: Inventory -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Inventory</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('items.index') }}" class="block bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Inventory Items</h3>
                            <p class="text-3xl font-bold text-teal-600 mt-2">{{ $allItems }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $lowStockItems }} low stock, {{ $outOfStockItems }} out of stock</p>
                        </div>
                        <div class="bg-teal-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Inventory Value</h3>
                            <p class="text-3xl font-bold text-teal-600 mt-2">₦{{ number_format($totalInventoryValue, 2) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Total stock value</p>
                        </div>
                        <div class="bg-teal-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <a href="{{ route('suppliers.index') }}" class="block bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Suppliers</h3>
                            <p class="text-3xl font-bold text-cyan-600 mt-2">{{ $totalSuppliers }}</p>
                        </div>
                        <div class="bg-cyan-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('categories.index') }}" class="block bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Categories</h3>
                            <p class="text-3xl font-bold text-purple-600 mt-2">{{ $totalCategories }}</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Transactions Card -->
        <div class="mb-8">
            <a href="{{ route('inventory-transactions.index') }}" class="block bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Inventory Transactions</h3>
                        <p class="text-2xl font-bold text-blue-600 mt-2">{{ $totalTransactions }}</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
        @endif

        <!-- Recent Activity -->
        @if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')))
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Recent Activity (Last 7 Days)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg border border-blue-200">
                    <h3 class="text-sm font-medium text-blue-700">New Projects</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $recentProjects }}</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg border border-green-200">
                    <h3 class="text-sm font-medium text-green-700">New Invoices</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $recentInvoices }}</p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-lg border border-purple-200">
                    <h3 class="text-sm font-medium text-purple-700">New Clients</h3>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $recentClients }}</p>
                </div>
                <div class="bg-gradient-to-br from-teal-50 to-teal-100 p-6 rounded-lg border border-teal-200">
                    <h3 class="text-sm font-medium text-teal-700">New Transactions</h3>
                    <p class="text-3xl font-bold text-teal-600 mt-2">{{ $recentTransactions }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
