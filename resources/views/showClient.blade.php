@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-3 pb-4 sm:px-5">
        <h1 class="mb-6 text-center text-2xl font-bold sm:text-3xl">All Clients</h1>

        <!-- Search Input -->
        <div class="mb-6 flex justify-stretch sm:justify-end">
            <input
                type="text"
                placeholder="Quick Search"
                class="w-full max-w-md rounded-lg border border-gray-300 p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 sm:w-1/3 sm:max-w-none"
            >
        </div>

        <!-- Table -->
        <div class="-mx-3 overflow-x-auto sm:mx-0">
            <table class="min-w-[44rem] w-full bg-white border border-gray-200 text-sm shadow-md rounded-lg sm:text-base">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 sm:px-6 sm:py-4 sm:text-sm">S/N</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 sm:px-6 sm:py-4 sm:text-sm">Client Name</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 sm:px-6 sm:py-4 sm:text-sm">Business Name</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 sm:px-6 sm:py-4 sm:text-sm">Email</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 sm:px-6 sm:py-4 sm:text-sm">Location</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 sm:px-6 sm:py-4 sm:text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($clients as $index => $client)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-3 sm:px-6 sm:py-4">{{ $index + 1 }}</td>
                            <td class="px-3 py-3 sm:px-6 sm:py-4">{{ $client->name }}</td>
                            <td class="px-3 py-3 sm:px-6 sm:py-4">{{ $client->business_name }}</td>
                            <td class="px-3 py-3 sm:px-6 sm:py-4">{{ $client->email }}</td>
                            <td class="px-3 py-3 sm:px-6 sm:py-4">{{ $client->location }}</td>
                            <td class="px-3 py-3 sm:px-6 sm:py-4">
                                <div class="flex min-w-0 flex-wrap gap-2">
                                <a href="{{ route('clients.show', $client->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600 transition">View</a>
                                <a href="{{ route('clients.edit', $client->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-yellow-600 transition">Update</a>
                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-600 transition" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($clients->isEmpty())
                <p class="text-center text-gray-500 mt-4">No clients found.</p>
            @endif
        </div>
    </div>
@endsection
