@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 pb-4">
        <h1 class="text-3xl font-bold mb-6 text-center">Update Client</h1>

        <!-- Form -->
        <form action="{{ route('clients.update', ['client' => $client->id]) }}" method="POST" class="bg-white p-8 rounded-lg shadow-md">
        @csrf
        @method('POST')


            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Client Name -->
                <div>
                    <label for="client_name" class="block text-sm font-medium text-gray-700">Client Name</label>
                    <input type="text" name="client_name" value="{{$client->client_name}}" id="client_name" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('client_name') }}">
                    @error('client_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Business Name -->
                <div>
                    <label for="business_name" class="block text-sm font-medium text-gray-700">Business Name</label>
                    <input type="text" name="business_name" value="{{$client->business_name ?? 'N/A'}}" id="business_name" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('business_name') }}">
                    @error('business_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Location -->
<div>
    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
    <input type="text" name="location" value="{{$client->location ?? ''}}" id="location" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('location')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<!-- Account Number -->
<div>
    <label for="account_number" class="block text-sm font-medium text-gray-700">Account Number</label>
    <input type="text" name="account_number" value="{{$client->account_number ?? ''}}" id="account_number" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('account_number')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<!-- Dish Serial Number -->
<div>
    <label for="dish_serial_number" class="block text-sm font-medium text-gray-700">Dish Serial Number</label>
    <input type="text" name="dish_serial_number" value="{{$client->dish_serial_number ?? ''}}" id="dish_serial_number" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('dish_serial_number')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<!-- Kit Number -->
<div>
    <label for="kit_number" class="block text-sm font-medium text-gray-700">Kit Number</label>
    <input type="text" name="kit_number" value="{{$client->kit_number ?? ''}}" id="kit_number" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('kit_number')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<!-- Starlink ID -->
<div>
    <label for="starlink_id" class="block text-sm font-medium text-gray-700">Starlink ID</label>
    <input type="text" name="starlink_id" value="{{$client->starlink_id ?? ''}}" id="starlink_id" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('starlink_id')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>
<!-- Starlink Password -->
<div>
    <label for="starlink_id" class="block text-sm font-medium text-gray-700">Starlink Password</label>
    <input type="text" name="starlink_id" value="{{$client->Password ?? ''}}" id="starlink_id" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('starlink_id')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<!-- Subscription Duration -->
<div>
    <label for="subscription_duration" class="block text-sm font-medium text-gray-700">Subscription Duration</label>
    <input type="text" name="subscription_duration" value="{{$client->subscription_duration ?? ''}}" id="subscription_duration" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('subscription_duration')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<!-- Subscription Start Date -->
<div>
    <label for="subscription_start_date" class="block text-sm font-medium text-gray-700">Subscription Start Date</label>
    <input type="date" name="subscription_start_date" value="{{$client->subscription_start_date ?? ''}}" id="subscription_start_date" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('subscription_start_date')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<!-- Subscription End Date -->
<div>
    <label for="subscription_end_date" class="block text-sm font-medium text-gray-700">Subscription End Date</label>
    <input type="date" name="subscription_end_date" value="{{$client->subscription_end_date ?? ''}}" id="subscription_end_date" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('subscription_end_date')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<!-- Email -->
<div>
    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
    <input type="email" name="email" value="{{$client->email ?? ''}}" id="email" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('email')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<!-- Service Address -->
<div>
    <label for="service_address" class="block text-sm font-medium text-gray-700">Service Address</label>
    <input type="text" name="service_address" value="{{$client->service_address ?? ''}}" id="service_address" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('service_address')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<!-- Account Name -->
<div>
    <label for="account_name" class="block text-sm font-medium text-gray-700">Account Name</label>
    <input type="text" name="account_name" value="{{$client->account_name ?? ''}}" id="account_name" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('account_name')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<!-- Card Details -->
<div>
    <label for="card_details" class="block text-sm font-medium text-gray-700">Card Details</label>
    <input type="text" name="card_details" value="{{$client->card_details ?? ''}}" id="card_details" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('card_details')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

            </div>

            <!-- Submit Button -->
            <div class="mt-6 text-center">
                <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-md shadow-md hover:bg-blue-600 transition">Update Client</button>
            </div>
        </form>
    </div>
@endsection
