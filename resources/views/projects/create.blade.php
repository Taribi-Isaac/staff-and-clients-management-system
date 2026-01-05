@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Create New Project</h1>

    <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow-md">
        @csrf

        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="project_name" class="block text-sm font-medium text-gray-700 mb-2">Project Name *</label>
                <input type="text" name="project_name" id="project_name" value="{{ old('project_name') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="e.g., Office Building Construction">
                @error('project_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="project_type" class="block text-sm font-medium text-gray-700 mb-2">Project Type *</label>
                <select name="project_type" id="project_type" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select Type --</option>
                    <option value="construction" {{ old('project_type') == 'construction' ? 'selected' : '' }}>Construction</option>
                    <option value="internet_installation" {{ old('project_type') == 'internet_installation' ? 'selected' : '' }}>Internet Installation</option>
                    <option value="networking" {{ old('project_type') == 'networking' ? 'selected' : '' }}>Networking</option>
                    <option value="solar_installation" {{ old('project_type') == 'solar_installation' ? 'selected' : '' }}>Solar Installation</option>
                    <option value="maintenance" {{ old('project_type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="consultation" {{ old('project_type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                    <option value="other" {{ old('project_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('project_type')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">Client Name (Optional)</label>
                <input type="text" name="client_name" id="client_name" value="{{ old('client_name') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter client name">
                @error('client_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-2">Client Phone (Optional)</label>
                <input type="text" name="client_phone" id="client_phone" value="{{ old('client_phone') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter client phone">
                @error('client_phone')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="client_email" class="block text-sm font-medium text-gray-700 mb-2">Client Email (Optional)</label>
                <input type="email" name="client_email" id="client_email" value="{{ old('client_email') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter client email">
                @error('client_email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" id="status" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="planning" {{ old('status') == 'planning' ? 'selected' : '' }}>Planning</option>
                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('start_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date (Optional)</label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('end_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">Budget (₦) *</label>
                <input type="number" name="budget" id="budget" value="{{ old('budget', 0) }}" step="0.01" min="0" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('budget')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="actual_cost" class="block text-sm font-medium text-gray-700 mb-2">Actual Cost (₦) (Optional)</label>
                <input type="number" name="actual_cost" id="actual_cost" value="{{ old('actual_cost') }}" step="0.01" min="0" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('actual_cost')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location (Optional)</label>
                <input type="text" name="location" id="location" value="{{ old('location') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="e.g., Port Harcourt, Rivers State">
                @error('location')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assigned To (Optional)</label>
                <select name="assigned_to" id="assigned_to" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="progress" class="block text-sm font-medium text-gray-700 mb-2">Progress (%) *</label>
                <input type="number" name="progress" id="progress" value="{{ old('progress', 0) }}" min="0" max="100" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('progress')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Description -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
            <textarea name="description" id="description" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Project description...">{{ old('description') }}</textarea>
            @error('description')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Notes -->
        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
            <textarea name="notes" id="notes" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Additional notes...">{{ old('notes') }}</textarea>
            @error('notes')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Project Photos -->
        <div class="mb-6">
            <label for="photos" class="block text-sm font-medium text-gray-700 mb-2">Project Photos (Optional)</label>
            <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            <p class="mt-1 text-sm text-gray-500">You can select multiple photos. Maximum file size: 5MB per image.</p>
            @error('photos.*')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('projects.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-md shadow-md hover:bg-gray-600 transition">Cancel</a>
            <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-md shadow-md hover:bg-red-700 transition">Create Project</button>
        </div>
    </form>
</div>
@endsection

