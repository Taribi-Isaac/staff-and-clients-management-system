@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Update Issue</h1>

    <!-- Form -->
    <form action="{{ route('issues.update', ['issue' => $issues->id]) }}" method="POST" class="bg-white p-8 rounded-lg shadow-md">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Client Name -->
            <div>
                <label for="issue_description" class="block text-sm font-medium text-gray-700">Issue Description</label>
                <input type="text" name="issue_description" id="issue_description" value="{{$issues->issue_description}}" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('client_name') }}">
                @error('issue_description')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Business Name -->
            <div>
                <label for="client_name" class="block text-sm font-medium text-gray-700">Client Name</label>
                <input type="text" name="client_name" id="client_name" value="{{$issues->client_name}}" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('business_name') }}">
                @error('client_name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Location -->
            <div>
                <label for="kit_number" class="block text-sm font-medium text-gray-700">Kit Number</label>
                <input type="text" name="kit_number" id="kit_number" value="{{$issues->kit_number}}"  class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('location') }}">
                @error('kit_number')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>




            <!-- Subscription Start Date -->
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Resolved Date</label>
                <input type="date" name="date" id="date" value="{{$issues->date}}" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('subscription_start_date') }}">
                @error('date')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Subscription End Date -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" value="{{$issues->date}}" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="received">Received</option>
                    <option value="in-progress">in-progress</option>
                    <option value="resolved">Resolved</option>
                </select>
                @error('status')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>



        </div>

        <!-- Submit Button -->
        <div class="mt-6 text-center">
            <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-md shadow-md hover:bg-blue-600 transition">Update Issue</button>
        </div>
    </form>
</div>
@endsection