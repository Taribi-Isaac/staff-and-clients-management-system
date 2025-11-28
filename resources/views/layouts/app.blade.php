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

<body class="font-sans antialiased">
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

        <!-- Page Content -->
        <div class="flex min-h-screen">

            <!-- Sidebar -->
            <div class="w-1/5 bg-gray-800 text-white p-6 h-auto min-h-screen">
                <ul class="space-y-4">

                    <li><a href="{{ route('home') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Home</a></li>
                    @if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')))
                    <li><a href="{{ route('invoices.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Invoices, Receipts & Quotes</a></li>
                    @endif
                    @if(auth()->user() && auth()->user()->hasRole('super-admin'))
                    <li><a href="{{ route('staff.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Staff</a></li>
                    @endif
                    <li><a href="{{ route('employees.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Employees</a></li>
                    <li><a href="{{ route('clients.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Clients</a></li>
                    <li><a href="{{ route('issues.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Issues</a></li>
                    @if(auth()->user() && auth()->user()->hasRole('super-admin'))
                    <li><a href="{{ route('admin.index') }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Admins</a></li>
                    @endif

                </ul>
            </div>

            <!-- Main Content -->
            <div class="ml-1/5 w-4/5 p-8 flex-grow">
                @yield('content')
            </div>
        </div>
</body>

</html>