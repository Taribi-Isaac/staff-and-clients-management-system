<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased overflow-x-hidden">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
        <header class="bg-red shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <div
            class="relative"
            x-data="{ sidebarOpen: false }"
            @keydown.escape.window="sidebarOpen = false"
        >
            <!-- Mobile: open app navigation -->
            <div class="flex items-center gap-2 bg-gray-800 px-3 py-2.5 text-white shadow md:hidden">
                <button
                    type="button"
                    class="inline-flex shrink-0 items-center gap-2 rounded-md bg-gray-700 px-3 py-2 text-sm font-medium hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-white/40"
                    @click="sidebarOpen = true"
                    :aria-expanded="sidebarOpen"
                    aria-controls="app-sidebar"
                    aria-label="Open navigation menu"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    Menu
                </button>
                <span class="truncate text-xs text-gray-300">App pages</span>
            </div>

            <!-- Dim background when mobile drawer is open -->
            <div
                x-show="sidebarOpen"
                x-cloak
                x-transition.opacity
                class="fixed inset-0 z-40 bg-black/50 md:hidden"
                @click="sidebarOpen = false"
                aria-hidden="true"
            ></div>

            <div class="flex min-h-0 md:min-h-[calc(100vh-5.5rem)]">
                <!-- Sidebar: off-canvas on small screens, column on md+ -->
                <aside
                    id="app-sidebar"
                    class="fixed inset-y-0 left-0 z-50 flex w-[min(18rem,92vw)] max-w-full flex-col bg-gray-800 p-4 text-white shadow-xl transition-transform duration-200 ease-out md:static md:z-0 md:w-64 md:max-w-none md:translate-x-0 md:shadow-none md:shrink-0"
                    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
                    role="navigation"
                    aria-label="Main navigation"
                >
                    <div class="mb-3 flex items-center justify-between border-b border-gray-600 pb-3 md:hidden">
                        <span class="text-sm font-semibold">Navigation</span>
                        <button
                            type="button"
                            class="rounded p-1.5 text-gray-300 hover:bg-gray-700 hover:text-white"
                            @click="sidebarOpen = false"
                            aria-label="Close menu"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <ul class="flex-1 space-y-1 overflow-y-auto overscroll-contain pb-4">
                        <li>
                            <a href="{{ route('home') }}" @click="sidebarOpen = false" class="block rounded px-3 py-2 text-sm hover:bg-gray-700 md:px-4 md:text-base">Home</a>
                        </li>
                        @if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')))
                        <li>
                            <a href="{{ route('invoices.index') }}" @click="sidebarOpen = false" class="block rounded px-3 py-2 text-sm hover:bg-gray-700 md:px-4 md:text-base">
                                <span class="md:hidden">Invoices &amp; quotes</span>
                                <span class="hidden md:inline">Invoices, Receipts &amp; Quotes</span>
                            </a>
                        </li>
                        <li><a href="{{ route('projects.index') }}" @click="sidebarOpen = false" class="block rounded px-3 py-2 text-sm hover:bg-gray-700 md:px-4 md:text-base">Projects</a></li>
                        <li><a href="{{ route('items.index') }}" @click="sidebarOpen = false" class="block rounded px-3 py-2 text-sm hover:bg-gray-700 md:px-4 md:text-base">Inventory</a></li>
                        <li><a href="{{ route('partners.index') }}" @click="sidebarOpen = false" class="block rounded px-3 py-2 text-sm hover:bg-gray-700 md:px-4 md:text-base">Partners</a></li>
                        <li><a href="{{ route('finance.index') }}" @click="sidebarOpen = false" class="block rounded px-3 py-2 text-sm hover:bg-gray-700 md:px-4 md:text-base">Finance</a></li>
                        @endif
                        @if(auth()->user() && auth()->user()->hasRole('super-admin'))
                        <li><a href="{{ route('staff.index') }}" @click="sidebarOpen = false" class="block rounded px-3 py-2 text-sm hover:bg-gray-700 md:px-4 md:text-base">Staff</a></li>
                        @endif
                        <li><a href="{{ route('employees.index') }}" @click="sidebarOpen = false" class="block rounded px-3 py-2 text-sm hover:bg-gray-700 md:px-4 md:text-base">Employees</a></li>
                        <li><a href="{{ route('clients.index') }}" @click="sidebarOpen = false" class="block rounded px-3 py-2 text-sm hover:bg-gray-700 md:px-4 md:text-base">Clients</a></li>
                        <li><a href="{{ route('tasks.index') }}" @click="sidebarOpen = false" class="block rounded px-3 py-2 text-sm hover:bg-gray-700 md:px-4 md:text-base">Tasks</a></li>
                        <li><a href="{{ route('issues.index') }}" @click="sidebarOpen = false" class="block rounded px-3 py-2 text-sm hover:bg-gray-700 md:px-4 md:text-base">Issues</a></li>
                        @if(auth()->user() && auth()->user()->hasRole('super-admin'))
                        <li><a href="{{ route('admin.index') }}" @click="sidebarOpen = false" class="block rounded px-3 py-2 text-sm hover:bg-gray-700 md:px-4 md:text-base">Admins</a></li>
                        @endif
                    </ul>
                </aside>

                <!-- Main content -->
                <div class="min-w-0 flex-1 px-3 py-4 sm:px-5 sm:py-6 md:p-8">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>

</html>
