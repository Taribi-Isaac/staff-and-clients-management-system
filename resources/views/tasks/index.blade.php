@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Task Management</h1>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Tasks</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['total_tasks'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Pending</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_tasks'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">In Progress</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['in_progress_tasks'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Completed</h3>
            <p class="text-3xl font-bold text-green-600">{{ $stats['completed_tasks'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Overdue</h3>
            <p class="text-3xl font-bold text-red-600">{{ $stats['overdue_tasks'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">My Created Tasks</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $stats['user_created_tasks'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Assigned to Me</h3>
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['user_assigned_tasks'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">My Completion Rate</h3>
            <p class="text-3xl font-bold text-green-600">{{ $stats['completion_rate'] }}%</p>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Top Task Creator</h3>
            @if($stats['top_creator'])
                <p class="text-xl font-bold">{{ $stats['top_creator']->name }}</p>
                <p class="text-gray-600">{{ $stats['top_creator']->created_tasks_count }} tasks created</p>
            @else
                <p class="text-gray-500">No data available</p>
            @endif
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Top Performer</h3>
            @if($stats['top_performer'])
                <p class="text-xl font-bold">{{ $stats['top_performer']->name }}</p>
                <p class="text-gray-600">{{ $stats['top_performer']->assigned_tasks_count }} tasks completed</p>
            @else
                <p class="text-gray-500">No data available</p>
            @endif
        </div>
    </div>

    <!-- Tasks by Department -->
    @if($stats['tasks_by_department']->isNotEmpty())
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Tasks by Department</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach($stats['tasks_by_department'] as $dept => $count)
            <div>
                <p class="font-semibold">{{ $dept }}</p>
                <p class="text-2xl font-bold text-blue-600">{{ $count }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Search and Filters -->
    <div class="flex flex-wrap justify-between mb-6 gap-4">
        <form action="{{ route('tasks.index') }}" method="GET" class="flex gap-2 flex-wrap">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search tasks..."
                class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            
            <select name="status" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <select name="priority" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">All Priorities</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
            </select>

            <select name="department" class="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">All Departments</option>
                <option value="Rodnav" {{ request('department') == 'Rodnav' ? 'selected' : '' }}>Rodnav</option>
                <option value="Solar Installation" {{ request('department') == 'Solar Installation' ? 'selected' : '' }}>Solar Installation</option>
                <option value="Networking" {{ request('department') == 'Networking' ? 'selected' : '' }}>Networking</option>
                <option value="Logistics" {{ request('department') == 'Logistics' ? 'selected' : '' }}>Logistics</option>
                <option value="General" {{ request('department') == 'General' ? 'selected' : '' }}>General</option>
            </select>

            <button type="submit" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">Search</button>
            <a href="{{ route('tasks.index') }}" class="bg-gray-500 text-white px-4 py-3 rounded-md shadow-md hover:bg-gray-600 transition">Clear</a>
        </form>
        
        <a href="{{ route('tasks.create') }}" class="bg-red-600 text-white px-4 py-3 rounded-md shadow-md hover:bg-red-700 transition">
            Create New Task
        </a>
    </div>

    <!-- Tasks Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead class="bg-red-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Title</th>
                    <th class="px-6 py-4 text-left font-semibold">Status</th>
                    <th class="px-6 py-4 text-left font-semibold">Priority</th>
                    <th class="px-6 py-4 text-left font-semibold">Department</th>
                    <th class="px-6 py-4 text-left font-semibold">Assigned To</th>
                    <th class="px-6 py-4 text-left font-semibold">Due Date</th>
                    <th class="px-6 py-4 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($tasks as $task)
                <tr class="hover:bg-gray-50 {{ $task->isOverdue() ? 'bg-red-50' : '' }}">
                    <td class="px-6 py-4">
                        <a href="{{ route('tasks.show', $task->id) }}" class="font-medium text-blue-600 hover:underline">
                            {{ $task->title }}
                        </a>
                        @if($task->isOverdue())
                            <span class="ml-2 text-xs text-red-600 font-semibold">OVERDUE</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'in_progress' => 'bg-blue-100 text-blue-800',
                                'on_hold' => 'bg-gray-100 text-gray-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded text-xs {{ $statusColors[$task->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $priorityColors = [
                                'low' => 'bg-green-100 text-green-800',
                                'medium' => 'bg-yellow-100 text-yellow-800',
                                'high' => 'bg-orange-100 text-orange-800',
                                'urgent' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded text-xs {{ $priorityColors[$task->priority] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $task->department ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        @if($task->assignedUsers->isNotEmpty())
                            @foreach($task->assignedUsers->take(2) as $user)
                                <span class="text-sm">{{ $user->name }}</span>
                                @if(!$loop->last), @endif
                            @endforeach
                            @if($task->assignedUsers->count() > 2)
                                <span class="text-sm text-gray-500">+{{ $task->assignedUsers->count() - 2 }} more</span>
                            @endif
                        @else
                            <span class="text-gray-500">Unassigned</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($task->due_date)
                            {{ $task->due_date->format('M d, Y') }}
                        @else
                            <span class="text-gray-500">No due date</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('tasks.show', $task->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">View</a>
                            @if($task->canEdit())
                                <a href="{{ route('tasks.edit', $task->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">Edit</a>
                            @endif
                            @if($task->created_by === auth()->id())
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this task? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Delete</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No tasks found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $tasks->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>
@endsection
