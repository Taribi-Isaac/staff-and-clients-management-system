@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- All Clients Card -->
            <a href="{{ route('staff.index') }}" class="block bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-700">All Staff</h2>
                <p class="text-4xl font-bold text-blue-600 mt-2">{{ $allStaff }}</p>
            </a>
            <!-- All Clients Card -->
            <a href="{{ route('employees.index') }}" class="block bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-700">All Employess</h2>
                <p class="text-4xl font-bold text-blue-600 mt-2">{{ $allEmployees}}</p>
            </a>
            <!-- All Clients Card -->
            <a href="{{ route('clients.index') }}" class="block bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-700">All Clients</h2>
                <p class="text-4xl font-bold text-blue-600 mt-2">{{ $allClients }}</p>
            </a>

            <!-- Active Clients Card -->
           <!--  <a href="#" class="block bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-700">Active Clients</h2>
            </a> -->

            <!-- Inactive Clients Card -->
           <!--  <a href="#" class="block bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-700">Inactive Clients</h2>
            </a> -->

            <!-- All Issues Card -->
            <a href="{{ route('issues.index') }}" class="block bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-700">All Issues</h2>
                <p class="text-4xl font-bold text-blue-600 mt-2">{{ $allIssues }}</p>
            </a>
        </div>
    </div>
@endsection
