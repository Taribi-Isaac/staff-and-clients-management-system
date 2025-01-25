@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-4">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-center mb-6">Staff Information</h2>

        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Staff Passport</h3>
            @if($staff->passport)
            <img src="{{ asset('storage/' . $staff->passport) }}" alt="Staff Passport" class="w-32 h-32 rounded-md shadow-md border border-gray-300">
            @else
            <p class="text-sm text-gray-500">No passport uploaded.</p>
            @endif
        </div>


        <!-- Staff Basic Information -->
        <div class="space-y-4">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->name }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->phone }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->email }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->start_date }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">End Date</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->end_date ?? 'N/A' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Role</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->role }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ ucfirst($staff->status) }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Employment Type</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ ucfirst($staff->employment_type) }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">State of Origin</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->state_of_origin }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Local Government Area</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->local_government_area }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Home Town</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->home_town }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Residential Address</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->residential_address }}</p>
            </div>
        </div>

        <!-- Guarantor 1 Information -->
        <h3 class="text-lg font-bold mb-4 mt-4">Guarantor 1</h3>
        <div class="space-y-4">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->guarantor_1_name }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->guarantor_1_phone }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->guarantor_1_address }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->guarantor_1_email }}</p>
            </div>
        </div>
        <!-- Guarantor 1 Information -->
        <h3 class="text-lg font-bold mb-4 mt-4">Guarantor 2</h3>
        <div class="space-y-4">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->guarantor_2_name }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->guarantor_2_phone }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->guarantor_2_address }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->guarantor_2_email }}</p>
            </div>
        </div>

        <!-- Bank Details -->
        <h3 class="text-lg font-bold mb-4">Bank Details</h3>
        <div class="space-y-4">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Account Name</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->account_name }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Account Number</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->account_number }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Bank Name</label>
                <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->bank_name }}</p>
            </div>
        </div>

        <!-- Submitted Documents -->
        <h3 class="text-lg font-bold mb-4">Submitted Documents</h3>
        <div class="mb-4">
            <p class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ $staff->submitted_documents }}</p>
        </div>
    </div>
</div>
@endsection