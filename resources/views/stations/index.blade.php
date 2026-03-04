@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Stations Management</h1>

    <!-- Navigation Tabs -->
    <div class="mb-6 flex justify-center">
        <div class="flex gap-2 bg-gray-100 p-1 rounded-lg">
            <a href="{{ route('partners.index') }}" class="px-6 py-2 rounded-md {{ request()->routeIs('partners.*') ? 'bg-red-600 text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                Partners
            </a>
            <a href="{{ route('stations.index') }}" class="px-6 py-2 rounded-md {{ request()->routeIs('stations.*') ? 'bg-red-600 text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                Stations
            </a>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="flex flex-wrap justify-between mb-6 gap-4">
        <form action="{{ route('stations.index') }}" method="GET" class="flex gap-2 flex-wrap">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name, address, state, or location"
                class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            
            <select name="partner" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">All Partners</option>
                @foreach($partners as $partner)
                    <option value="{{ $partner->id }}" {{ request('partner') == $partner->id ? 'selected' : '' }}>{{ $partner->name }}</option>
                @endforeach
            </select>
            
            <button type="submit" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">Search</button>
            <a href="{{ route('stations.index') }}" class="bg-gray-500 text-white px-4 py-3 rounded-md shadow-md hover:bg-gray-600 transition">Clear</a>
        </form>
        
        <div class="flex gap-2">
            <a href="{{ route('stations.create') }}" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">
                Add Station
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead class="bg-red-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Station</th>
                    <th class="px-6 py-4 text-left font-semibold">Partner</th>
                    <th class="px-6 py-4 text-left font-semibold">Address</th>
                    <th class="px-6 py-4 text-left font-semibold">State/Location</th>
                    <th class="px-6 py-4 text-left font-semibold">Primary Contact</th>
                    <th class="px-6 py-4 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($stations as $station)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">{{ $station->name ?? 'Station #' . $station->id }}</td>
                    <td class="px-6 py-4">
                        <div>{{ $station->partner->name }}</div>
                    </td>
                    <td class="px-6 py-4">{{ Str::limit($station->address, 50) }}</td>
                    <td class="px-6 py-4">
                        @if($station->state) {{ $station->state }} @endif
                        @if($station->state && $station->location) / @endif
                        @if($station->location) {{ $station->location }} @endif
                        @if(!$station->state && !$station->location) N/A @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($station->primary_contact_name)
                            <div>{{ $station->primary_contact_name }}</div>
                            @if($station->primary_contact_phone)
                                <div class="text-xs text-gray-500">{{ $station->primary_contact_phone }}</div>
                            @endif
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            <a href="{{ route('stations.show', $station->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">View</a>
                            <a href="{{ route('stations.edit', $station->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 transition">Edit</a>
                            @if(auth()->user() && auth()->user()->hasRole('super-admin'))
                            <form action="{{ route('stations.destroy', $station->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition w-full" onclick="return confirm('Are you sure you want to delete this station?')">Delete</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No stations found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $stations->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>
@endsection
