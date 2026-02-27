@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Partners Management</h1>

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

    <!-- Search and Actions -->
    <div class="flex flex-wrap justify-between mb-6 gap-4">
        <form action="{{ route('partners.index') }}" method="GET" class="flex gap-2 flex-wrap">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name, email, or phone"
                class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            
            <button type="submit" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">Search</button>
            <a href="{{ route('partners.index') }}" class="bg-gray-500 text-white px-4 py-3 rounded-md shadow-md hover:bg-gray-600 transition">Clear</a>
        </form>

        <a href="{{ route('partners.create') }}" class="bg-red-600 text-white px-6 py-3 rounded-md shadow-md hover:bg-red-700 transition">
            Add New Partner
        </a>
    </div>

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

    <!-- Partners Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacts</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stations</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($partners as $partner)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $partner->name }}</div>
                        @if($partner->head_office_address)
                            <div class="text-sm text-gray-500">{{ Str::limit($partner->head_office_address, 50) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $partner->email ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $partner->phone ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $partner->contacts->count() }} contact(s)</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $partner->stations->count() }} station(s)</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('partners.show', $partner->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                        <a href="{{ route('partners.edit', $partner->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                        @if(auth()->user() && auth()->user()->hasRole('super-admin'))
                        <form action="{{ route('partners.destroy', $partner->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this partner?')">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No partners found. <a href="{{ route('partners.create') }}" class="text-red-600 hover:underline">Create one now</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $partners->links() }}
    </div>
</div>
@endsection
