@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">All Issues</h1>

    <!-- Search Input -->
    <div class="flex justify-between mb-6">
        <form action="{{ route('clients.index') }}" method="GET">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Quick Search"
                class="w-100 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-3 rounded-md shadow-md hover:bg-blue-600 transition">Search</button>
        </form>
        <div class="ml-2 bg-blue-500 text-white px-4 py-3 rounded-md shadow-md hover:bg-blue-600 transition"><a href="{{route('issues.create')}}">
                Create New
            </a></div>

    </div>


    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold text-gray-700">S/N</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Issue Description</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Client Name</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Kit Number</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Data</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($issues as $index => $issue)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">{{ $issue->issue_description }}</td>
                    <td class="px-6 py-4">{{ $issue->client_name }}</td>
                    <td class="px-6 py-4">{{ $issue->kit_number }}</td>
                    <td class="px-6 py-4">{{ $issue->date }}</td>
                    <td class="px-6 py-4">
                        <div
                            class="
            px-4 py-2 mb-1 rounded-md shadow-md text-white transition 
            @if($issue->status === 'received') bg-yellow-500 hover:bg-yellow-600 
            @elseif($issue->status === 'in-progress') bg-red-500 hover:bg-red-600 
            @elseif($issue->status === 'resolved') bg-green-500 hover:bg-green-600 
            @else bg-gray-400 hover:bg-gray-500 
            @endif
        ">
                            {{ $issue->status }}
                        </div>
                    </td>
                    <td class="px-6 py-4 flex flex-col justify-between items-center space-x-2">


                        <a href="{{ route('issues.show', $issue->id) }}" class="bg-blue-500 text-white px-4 py-2 mb-1 rounded-md shadow-md hover:bg-yellow-600 transition">Update</a>


                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($issues->isEmpty())
        <p class="text-center text-gray-500 mt-4">No issues found.</p>
        @endif
    </div>
    <!-- Pagination Links -->
    <div class="mt-6">
        {{ $issues->appends(['search' => request('search')])->links('pagination::tailwind') }}
    </div>

</div>
@endsection