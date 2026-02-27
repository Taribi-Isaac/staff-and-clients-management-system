@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Add New Station</h1>

    <form action="{{ route('stations.store') }}" method="POST" class="bg-white p-8 rounded-lg shadow-md">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="partner_id" class="block text-sm font-medium text-gray-700 mb-2">Partner/Company *</label>
                <select name="partner_id" id="partner_id" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select Partner --</option>
                    @foreach($partners as $partner)
                        <option value="{{ $partner->id }}" {{ old('partner_id') == $partner->id ? 'selected' : '' }}>
                            {{ $partner->name }}
                        </option>
                    @endforeach
                </select>
                @error('partner_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Station Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="e.g., Main Station, Branch Office">
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Station Address *</label>
                <textarea name="address" id="address" rows="2" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Full address of the station">{{ old('address') }}</textarea>
                @error('address')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                <input type="text" name="state" id="state" value="{{ old('state') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="e.g., Lagos, Abuja">
                @error('state')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                <input type="text" name="location" id="location" value="{{ old('location') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="e.g., Ikeja, Victoria Island">
                @error('location')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="assets" class="block text-sm font-medium text-gray-700 mb-2">Assets/Items at this Station</label>
                <textarea name="assets" id="assets" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="List of items/assets at this station (e.g., Laptop - 5 units, Router - 2 units)">{{ old('assets') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Enter a description of assets or items located at this station</p>
                @error('assets')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Primary Contact Information -->
        <div class="mb-6 border-t pt-6">
            <h2 class="text-xl font-semibold mb-4">Primary Contact Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="primary_contact_name" class="block text-sm font-medium text-gray-700 mb-2">Contact Name</label>
                    <input type="text" name="primary_contact_name" id="primary_contact_name" value="{{ old('primary_contact_name') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Full name">
                    @error('primary_contact_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="primary_contact_email" class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                    <input type="email" name="primary_contact_email" id="primary_contact_email" value="{{ old('primary_contact_email') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Email address">
                    @error('primary_contact_email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="primary_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                    <input type="text" name="primary_contact_phone" id="primary_contact_phone" value="{{ old('primary_contact_phone') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Phone number">
                    @error('primary_contact_phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 text-center">
            <button type="submit" class="bg-red-600 text-white px-8 py-3 rounded-md shadow-md hover:bg-red-700 transition text-lg font-semibold">Create Station</button>
            <a href="{{ route('stations.index') }}" class="ml-4 bg-gray-500 text-white px-8 py-3 rounded-md shadow-md hover:bg-gray-600 transition text-lg font-semibold">Cancel</a>
        </div>
    </form>
</div>
@endsection
