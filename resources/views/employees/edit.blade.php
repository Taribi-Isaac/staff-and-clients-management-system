@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-4">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-center mb-6">Update Employee</h2>

        <form action="{{ route('employees.update', ['employees'=> $employees->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-500 @enderror" value="{{ old('name', $employees->name) }}" required>
                @error('name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" id="phone" name="phone" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('phone') border-red-500 @enderror" value="{{ old('phone', $employees->phone) }}" required>
                @error('phone')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-500 @enderror" value="{{ old('email', $employees->email) }}" required>
                @error('email')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('start_date') border-red-500 @enderror" value="{{ old('start_date', $employees->start_date) }}" required>
                @error('start_date')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" id="end_date" name="end_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('end_date') border-red-500 @enderror" value="{{ old('end_date', $employees->end_date) }}">
                @error('end_date')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <input type="text" id="role" name="role" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('role') border-red-500 @enderror" value="{{ old('role', $employees->role) }}" required>
                @error('role')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('status') border-red-500 @enderror" required>
                    <option value="active" {{ old('status', $employees->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $employees->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="leave" {{ old('status', $employees->status) == 'leave' ? 'selected' : '' }}>Leave</option>
                    <option value="suspension" {{ old('status', $employees->status) == 'suspension' ? 'selected' : '' }}>Suspension</option>
                </select>
                @error('status')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="employment_type" class="block text-sm font-medium text-gray-700">Employment Type</label>
                <select id="employment_type" name="employment_type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('employment_type') border-red-500 @enderror" required>
                    <option value="full-time" {{ old('employment_type', $employees->employment_type) == 'full-time' ? 'selected' : '' }}>Full-Time</option>
                    <option value="contract" {{ old('employment_type', $employees->employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                    <option value="intern" {{ old('employment_type', $employees->employment_type) == 'intern' ? 'selected' : '' }}>Intern</option>
                </select>
                @error('employment_type')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="station_id" class="block text-sm font-medium text-gray-700">Station</label>
                <select id="station_id" name="station_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('station_id') border-red-500 @enderror">
                    <option value="">-- Select Station (Optional) --</option>
                    @foreach($stations as $station)
                        <option value="{{ $station->id }}" {{ old('station_id', $employees->station_id) == $station->id ? 'selected' : '' }}>
                            {{ $station->name ?? 'Station #' . $station->id }} - {{ $station->partner->name }} ({{ $station->address }})
                        </option>
                    @endforeach
                </select>
                @error('station_id')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="passport" class="block text-sm font-medium text-gray-700">Passport</label>
                <input type="file" id="passport" name="passport" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('passport') border-red-500 @enderror">
                @error('passport')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="state_of_origin" class="block text-sm font-medium text-gray-700">State of Origin</label>
                <input type="text" id="state_of_origin" name="state_of_origin" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('state_of_origin') border-red-500 @enderror" value="{{ old('state_of_origin') }}" required>
                @error('state_of_origin')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="local_government_area" class="block text-sm font-medium text-gray-700">Local Government Area</label>
                <input type="text" id="local_government_area" name="local_government_area" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('local_government_area') border-red-500 @enderror" value="{{ old('local_government_area') }}" required>
                @error('local_government_area')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="home_town" class="block text-sm font-medium text-gray-700">Home Town</label>
                <input type="text" id="home_town" name="home_town" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('home_town') border-red-500 @enderror" value="{{ old('home_town') }}">
                @error('home_town')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="residential_address" class="block text-sm font-medium text-gray-700">Resident Address</label>
                <input type="text" id="residential_address" name="residential_address" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('residential_address') border-red-500 @enderror" value="{{ old('residential_address') }}">
                @error('residential_address')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Guarantor 1</h3>
                <label for="guarantor_1_name" class="block text-sm font-medium text-gray-700">Guarantor Name</label>
                <input type="text" id="guarantor_1_name" name="guarantor_1_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('guarantor_1_name') border-red-500 @enderror" value="{{ old('guarantor_1_name') }}">
                @error('guarantor_1_name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror

                <label for="guarantor_1_phone" class="block text-sm font-medium text-gray-700">Guarantor Phone</label>
                <input type="text" id="guarantor_1_phone" name="guarantor_1_phone" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('guarantor_1_phone') border-red-500 @enderror" value="{{ old('guarantor_1_phone') }}">
                @error('guarantor_1_phone')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <label for="guarantor_1_address" class="block text-sm font-medium text-gray-700">Guarantor  Address</label>
                <input type="text" id="guarantor_1_address" name="guarantor_1_address" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('guarantor_1_address') border-red-500 @enderror" value="{{ old('guarantor_1_address') }}">
                @error('guarantor_1_address')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror

                <label for="guarantor_1_email" class="block text-sm font-medium text-gray-700">Guarantor Email</label>
                <input type="email" id="guarantor_1_email" name="guarantor_1_email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('guarantor_1_email') border-red-500 @enderror" value="{{ old('guarantor_1_email') }}">
                @error('guarantor_1_email')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Guarantor 2</h3>
                <label for="guarantor_2_name" class="block text-sm font-medium text-gray-700">Guarantor Name</label>
                <input type="text" id="guarantor_2_name" name="guarantor_2_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('guarantor_2_name') border-red-500 @enderror" value="{{ old('guarantor_2_name') }}">
                @error('guarantor_2_name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror

                <label for="guarantor_2_phone" class="block text-sm font-medium text-gray-700">Guarantor Phone</label>
                <input type="text" id="guarantor_2_phone" name="guarantor_2_phone" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('guarantor_2_phone') border-red-500 @enderror" value="{{ old('guarantor_2_phone') }}">
                @error('guarantor_2_phone')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <label for="guarantor_2_address" class="block text-sm font-medium text-gray-700">Guarantor Address</label>
                <input type="text" id="guarantor_2_address" name="guarantor_2_address" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('guarantor_2_address') border-red-500 @enderror" value="{{ old('guarantor_2_address') }}">
                @error('guarantor_2_address')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror

                <label for="guarantor_2_email" class="block text-sm font-medium text-gray-700">Guarantor Email</label>
                <input type="email" id="guarantor_2_email" name="guarantor_2_email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('guarantor_2_email') border-red-500 @enderror" value="{{ old('guarantor_2_email') }}">
                @error('guarantor_2_email')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Bank Info</h3>
                <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name</label>
                <input type="text" id="bank_name" name="bank_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('bank_name') border-red-500 @enderror" value="{{ old('bank_name') }}">
                @error('bank_name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror

                <label for="account_number" class="block text-sm font-medium text-gray-700">Account Number</label>
                <input type="text" id="account_number" name="account_number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('account_number') border-red-500 @enderror" value="{{ old('account_number') }}">
                @error('account_number')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror

                <label for="account_name" class="block text-sm font-medium text-gray-700">Account Name</label>
                <input type="text" id="account_name" name="account_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('account_name') border-red-500 @enderror" value="{{ old('account_name') }}">
                @error('account_name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
             <!-- Submitted Documents Section -->
             <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Submitted Documents</h3>

                <label for="submit_doc_1" class="block text-sm font-medium text-gray-700">ID Card (Upload)</label>
                <input type="file" id="submit_doc_1" name="submit_doc_1" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('submit_doc_1') border-red-500 @enderror">
                @error('submit_doc_1')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror

                <label for="submit_doc_2" class="block text-sm font-medium text-gray-700">Resume (Upload)</label>
                <input type="file" id="submit_doc_2" name="submit_doc_2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('submit_doc_2') border-red-500 @enderror">
                @error('submit_doc_2')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror

                <label for="submit_doc_3" class="block text-sm font-medium text-gray-700">Certificate (Upload)</label>
                <input type="file" id="submit_doc_3" name="submit_doc_3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('submit_doc_3') border-red-500 @enderror">
                @error('submit_doc_3')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>


            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md">Save</button>
        </form>
    </div>
</div>
@endsection
