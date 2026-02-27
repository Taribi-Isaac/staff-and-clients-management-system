@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $station->name ?? 'Station #' . $station->id }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('stations.edit', $station->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-yellow-600 transition">Edit</a>
            <a href="{{ route('stations.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-gray-600 transition">Back to Stations</a>
            @if(auth()->user() && auth()->user()->hasRole('super-admin'))
            <form action="{{ route('stations.destroy', $station->id) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-600 transition" onclick="return confirm('Are you sure you want to delete this station?')">Delete</button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Station Details -->
    <div class="bg-white p-8 rounded-lg shadow-md mb-6">
        <h2 class="text-2xl font-semibold mb-6">Station Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Partner/Company</label>
                <p class="text-lg font-semibold">
                    <a href="{{ route('partners.show', $station->partner->id) }}" class="text-red-600 hover:underline">
                        {{ $station->partner->name }}
                    </a>
                </p>
            </div>

            @if($station->name)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Station Name</label>
                <p class="text-lg font-semibold">{{ $station->name }}</p>
            </div>
            @endif

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <p class="text-lg">{{ $station->address }}</p>
            </div>

            @if($station->state)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                <p class="text-lg">{{ $station->state }}</p>
            </div>
            @endif

            @if($station->location)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                <p class="text-lg">{{ $station->location }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Primary Contact -->
    @if($station->primary_contact_name || $station->primary_contact_email || $station->primary_contact_phone)
    <div class="bg-white p-8 rounded-lg shadow-md mb-6">
        <h2 class="text-2xl font-semibold mb-6">Primary Contact</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @if($station->primary_contact_name)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <p class="text-lg">{{ $station->primary_contact_name }}</p>
            </div>
            @endif

            @if($station->primary_contact_email)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <p class="text-lg">{{ $station->primary_contact_email }}</p>
            </div>
            @endif

            @if($station->primary_contact_phone)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <p class="text-lg">{{ $station->primary_contact_phone }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Assets -->
    @if($station->assets)
    <div class="bg-white p-8 rounded-lg shadow-md mb-6">
        <h2 class="text-2xl font-semibold mb-6">Assets/Items at this Station</h2>
        <div class="whitespace-pre-wrap text-lg">{{ $station->assets }}</div>
    </div>
    @endif
</div>
@endsection
