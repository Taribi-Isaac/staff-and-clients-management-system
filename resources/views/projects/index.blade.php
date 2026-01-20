@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Projects Management</h1>

    <!-- Search and Filters -->
    <div class="flex flex-wrap justify-between mb-6 gap-4">
        <form action="{{ route('projects.index') }}" method="GET" class="flex gap-2 flex-wrap">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by project name, description, or location"
                class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            
            <select name="type" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">All Types</option>
                <option value="construction" {{ request('type') == 'construction' ? 'selected' : '' }}>Construction</option>
                <option value="internet_installation" {{ request('type') == 'internet_installation' ? 'selected' : '' }}>Internet Installation</option>
                <option value="networking" {{ request('type') == 'networking' ? 'selected' : '' }}>Networking</option>
                <option value="solar_installation" {{ request('type') == 'solar_installation' ? 'selected' : '' }}>Solar Installation</option>
                <option value="maintenance" {{ request('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                <option value="consultation" {{ request('type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
            </select>

            <select name="status" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">All Statuses</option>
                <option value="planning" {{ request('status') == 'planning' ? 'selected' : '' }}>Planning</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <button type="submit" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">Search</button>
            <a href="{{ route('projects.index') }}" class="bg-gray-500 text-white px-4 py-3 rounded-md shadow-md hover:bg-gray-600 transition">Clear</a>
        </form>
        
        <a href="{{ route('projects.create') }}" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">
            Create New Project
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead class="bg-red-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Project Name</th>
                    <th class="px-6 py-4 text-left font-semibold">Type</th>
                    <th class="px-6 py-4 text-left font-semibold">Client</th>
                    <th class="px-6 py-4 text-left font-semibold">Status</th>
                    <th class="px-6 py-4 text-left font-semibold">Progress</th>
                    <th class="px-6 py-4 text-left font-semibold">Start Date</th>
                    <th class="px-6 py-4 text-left font-semibold">Budget</th>
                    <th class="px-6 py-4 text-left font-semibold">Assigned To</th>
                    <th class="px-6 py-4 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($projects as $project)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">{{ $project->project_name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                            {{ $project->project_type_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $project->client->client_name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs {{ $project->status_color }}">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $project->progress }}%"></div>
                            </div>
                            <span class="text-sm">{{ $project->progress }}%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">{{ $project->start_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4 font-semibold">₦{{ number_format($project->budget, 2) }}</td>
                    <td class="px-6 py-4">{{ $project->assignedUser->name ?? 'Unassigned' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            <a href="{{ route('projects.show', $project->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">View</a>
                            <a href="{{ route('projects.edit', $project->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 transition">Edit</a>
                            @if(auth()->user()->hasRole('super-admin'))
                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition w-full" onclick="return confirm('Are you sure you want to delete this project?')">Delete</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">No projects found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-6">
        {{ $projects->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>
@endsection





