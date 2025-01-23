@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6">
        <h1 class="text-3xl font-bold mb-6 text-center">All Clients</h1>
        
        <!-- Search Input -->
        <div class="flex justify-end mb-6">
            <input 
                type="text" 
                placeholder="Quick Search" 
                class="w-1/3 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">S/N</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Client Name</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Business Name</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($clients as $index => $client)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">{{ $client->name }}</td>
                            <td class="px-6 py-4">{{ $client->business_name }}</td>
                            <td class="px-6 py-4">{{ $client->email }}</td>
                            <td class="px-6 py-4">{{ $client->location }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('clients.show', $client->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600 transition">View</a>
                                <a href="{{ route('clients.edit', $client->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-yellow-600 transition">Update</a>
                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-600 transition" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
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
