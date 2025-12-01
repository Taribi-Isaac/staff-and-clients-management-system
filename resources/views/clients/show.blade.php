@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 pb-4">
        <h1 class="text-3xl font-bold mb-6 text-center">Client Details</h1>

        <!-- Client Information -->
        <div class="bg-white p-8 rounded-lg shadow-md">
            <div class="flex flex-row justify-between">
            <h2 class="text-2xl font-semibold mb-4">Client Information</h2>
            <a href="{{ route('clients.edit', $client->id) }}" class="bg-blue-500 text-white px-4 py-2 mb-1 rounded-md shadow-md hover:bg-blue-600 transition">Edit</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Client Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Client Name</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->client_name }}</p>
                </div>

                <!-- Business Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Business Name</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->business_name ?? 'N/A' }}</p>
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Location</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->location ?? 'N/A' }}</p>
                </div>

                <!-- Account Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Account Number</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->account_number }}</p>
                </div>

                <!-- Dish Serial Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dish Serial Number</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->dish_serial_number ?? 'N/A' }}</p>
                </div>

                <!-- Kit Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kit Number</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->kit_number ?? 'N/A' }}</p>
                </div>

                <!-- Starlink ID -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Starlink ID</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->starlink_id ?? 'N/A' }}</p>
                </div>
                <!-- Starlink Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Starlink Password</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->password ?? 'N/A' }}</p>
                </div>

                <!-- Subscription Duration -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subscription Duration</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->subscription_duration ?? 'N/A' }}</p>
                </div>

                <!-- Subscription Start Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subscription Start Date</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->subscription_start_date ?? 'N/A' }}</p>
                </div>

                <!-- Subscription End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subscription End Date</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->subscription_end_date ?? 'N/A' }}</p>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->email }}</p>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->phone ?? 'N/A' }}</p>
                </div>

                <!-- Service Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Service Address</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->service_address ?? 'N/A' }}</p>
                </div>

                <!-- Account Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Account Name</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->account_name ?? 'N/A' }}</p>
                </div>

                <!-- Card Details -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Card Details</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->card_details ?? 'N/A' }}</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <p class="mt-1 p-3 border border-gray-300 rounded-lg">{{ $client->status }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
