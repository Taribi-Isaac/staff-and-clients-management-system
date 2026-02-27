@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $partner->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('partners.edit', $partner->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-yellow-600 transition">Edit</a>
            <a href="{{ route('partners.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-gray-600 transition">Back to Partners</a>
            @if(auth()->user() && auth()->user()->hasRole('super-admin'))
            <form action="{{ route('partners.destroy', $partner->id) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-600 transition" onclick="return confirm('Are you sure you want to delete this partner?')">Delete</button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Partner Details -->
    <div class="bg-white p-8 rounded-lg shadow-md mb-6">
        <h2 class="text-2xl font-semibold mb-6">Company Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                <p class="text-lg font-semibold">{{ $partner->name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <p class="text-lg">{{ $partner->email ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <p class="text-lg">{{ $partner->phone ?? 'N/A' }}</p>
            </div>

            @if($partner->head_office_address)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Head Office Address</label>
                <p class="text-lg">{{ $partner->head_office_address }}</p>
            </div>
            @endif

            @if($partner->address)
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <p class="text-lg">{{ $partner->address }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Primary Contacts -->
    @if($partner->contacts->count() > 0)
    <div class="bg-white p-8 rounded-lg shadow-md mb-6">
        <h2 class="text-2xl font-semibold mb-6">Primary Contacts</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($partner->contacts as $contact)
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-lg mb-2">{{ $contact->name }}</h3>
                @if($contact->phone)
                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Phone:</span> {{ $contact->phone }}</p>
                @endif
                @if($contact->email)
                    <p class="text-sm text-gray-600"><span class="font-medium">Email:</span> {{ $contact->email }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Stations -->
    @if($partner->stations->count() > 0)
    <div class="bg-white p-8 rounded-lg shadow-md mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Stations ({{ $partner->stations->count() }})</h2>
            <a href="{{ route('stations.index', ['partner' => $partner->id]) }}" class="text-red-600 hover:underline">View All Stations</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($partner->stations->take(4) as $station)
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-lg mb-2">{{ $station->name ?? 'Station #' . $station->id }}</h3>
                <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Address:</span> {{ $station->address }}</p>
                @if($station->state)
                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">State:</span> {{ $station->state }}</p>
                @endif
                @if($station->location)
                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Location:</span> {{ $station->location }}</p>
                @endif
                @if($station->primary_contact_name)
                    <p class="text-sm text-gray-600"><span class="font-medium">Contact:</span> {{ $station->primary_contact_name }}</p>
                @endif
                <a href="{{ route('stations.show', $station->id) }}" class="text-red-600 hover:underline text-sm mt-2 inline-block">View Details</a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
