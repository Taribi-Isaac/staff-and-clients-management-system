@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $project->project_name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('projects.edit', $project->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-yellow-600 transition">Edit</a>
            @if(auth()->user()->hasRole('super-admin'))
            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-600 transition" onclick="return confirm('Are you sure you want to delete this project?')">Delete</button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Project Details -->
    <div class="bg-white p-8 rounded-lg shadow-md">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Project Type</label>
                <p class="text-lg font-semibold">
                    <span class="px-3 py-1 rounded text-sm bg-blue-100 text-blue-800">
                        {{ $project->project_type_label }}
                    </span>
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <p class="text-lg font-semibold">
                    <span class="px-3 py-1 rounded text-sm {{ $project->status_color }}">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                <p class="text-lg">{{ $project->client_name ?? ($project->client->client_name ?? 'N/A') }}</p>
            </div>

            @if($project->client_phone)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Client Phone</label>
                <p class="text-lg">{{ $project->client_phone }}</p>
            </div>
            @endif

            @if($project->client_email)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Client Email</label>
                <p class="text-lg">{{ $project->client_email }}</p>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                <p class="text-lg">{{ $project->assignedUser->name ?? 'Unassigned' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <p class="text-lg">{{ $project->start_date->format('M d, Y') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <p class="text-lg">{{ $project->end_date ? $project->end_date->format('M d, Y') : 'Not set' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Budget</label>
                <p class="text-lg font-semibold text-green-600">₦{{ number_format($project->budget, 2) }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Actual Cost</label>
                <p class="text-lg font-semibold {{ $project->actual_cost ? ($project->actual_cost > $project->budget ? 'text-red-600' : 'text-green-600') : 'text-gray-500' }}">
                    {{ $project->actual_cost ? '₦' . number_format($project->actual_cost, 2) : 'Not recorded' }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                <p class="text-lg">{{ $project->location ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Created By</label>
                <p class="text-lg">{{ $project->creator->name ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
                <label class="block text-sm font-medium text-gray-700">Progress</label>
                <span class="text-lg font-semibold">{{ $project->progress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-blue-600 h-4 rounded-full transition-all duration-300" style="width: {{ $project->progress }}%"></div>
            </div>
        </div>

        <!-- Description -->
        @if($project->description)
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $project->description }}</p>
        </div>
        @endif

        <!-- Notes -->
        @if($project->notes)
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $project->notes }}</p>
        </div>
        @endif

        <!-- Budget vs Actual Cost -->
        @if($project->actual_cost)
        <div class="mt-6 p-4 rounded-lg {{ $project->actual_cost > $project->budget ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200' }}">
            <div class="flex justify-between items-center">
                <span class="font-medium">Budget Variance:</span>
                <span class="font-bold {{ $project->actual_cost > $project->budget ? 'text-red-600' : 'text-green-600' }}">
                    {{ $project->actual_cost > $project->budget ? '+' : '' }}₦{{ number_format(abs($project->actual_cost - $project->budget), 2) }}
                    ({{ $project->actual_cost > $project->budget ? 'Over' : 'Under' }} budget)
                </span>
            </div>
        </div>
        @endif

        <!-- Project Photos -->
        @if($project->photos && count($project->photos) > 0)
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-4">Project Photos</label>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($project->photos as $photo)
                <div class="relative group">
                    <img src="{{ asset('storage/' . $photo) }}" alt="Project photo" class="w-full h-64 object-cover rounded-lg border border-gray-300 cursor-pointer hover:opacity-90 transition">
                    <a href="{{ asset('storage/' . $photo) }}" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 group-hover:bg-opacity-50 transition rounded-lg">
                        <svg class="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                        </svg>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="mt-4">
        <a href="{{ route('projects.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-gray-600 transition">Back to Projects</a>
    </div>
</div>
@endsection

