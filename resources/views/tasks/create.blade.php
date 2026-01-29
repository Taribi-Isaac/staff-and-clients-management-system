@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Create New Task</h1>

    <form action="{{ route('tasks.store') }}" method="POST" id="taskForm" class="bg-white p-8 rounded-lg shadow-md">
        @csrf

        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter task title">
                @error('title')
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
                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('due_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" id="status" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
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
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                <select name="priority" id="priority" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
                @error('priority')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department / Venture</label>
                <select name="department" id="department" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">Select Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
                @error('department')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Assign To (Select Multiple)</label>
                <div class="border border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto bg-gray-50">
                    <div class="space-y-2">
                        @foreach($users as $user)
                            <label class="flex items-center p-2 hover:bg-white rounded cursor-pointer">
                                <input type="checkbox" 
                                       name="assigned_users[]" 
                                       value="{{ $user->id }}" 
                                       {{ in_array($user->id, old('assigned_users', [])) ? 'checked' : '' }}
                                       class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                <span class="ml-3 text-sm text-gray-700">
                                    <span class="font-medium">{{ $user->name }}</span>
                                    <span class="text-gray-500">({{ $user->email }})</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Tap to select/deselect users</p>
                @error('assigned_users')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Description -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="6" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter task description...">{{ old('description') }}</textarea>
            @error('description')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Subtasks / Checklist -->
        <div class="mb-6 border-t pt-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Subtasks / Checklist</h2>
                <button type="button" id="addSubtask" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">Add Subtask</button>
            </div>
            <div id="subtasksContainer">
                <!-- Subtasks will be added here dynamically -->
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 text-center">
            <button type="submit" class="bg-red-600 text-white px-8 py-3 rounded-md shadow-md hover:bg-red-700 transition text-lg font-semibold">Create Task</button>
            <a href="{{ route('tasks.index') }}" class="ml-4 bg-gray-500 text-white px-8 py-3 rounded-md shadow-md hover:bg-gray-600 transition text-lg font-semibold">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let subtaskIndex = 0;

    // Add subtask
    document.getElementById('addSubtask').addEventListener('click', function() {
        const container = document.getElementById('subtasksContainer');
        const newSubtask = document.createElement('div');
        newSubtask.className = 'subtask-row mb-4 p-4 border border-gray-200 rounded-lg';
        newSubtask.innerHTML = `
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-10">
                    <input type="text" name="subtasks[${subtaskIndex}][title]" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Subtask title">
                    <textarea name="subtasks[${subtaskIndex}][description]" rows="2" class="w-full p-2 mt-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Subtask description (optional)"></textarea>
                </div>
                <div class="col-span-2 flex items-start">
                    <button type="button" class="remove-subtask bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition">Remove</button>
                </div>
            </div>
        `;
        container.appendChild(newSubtask);
        subtaskIndex++;
    });

    // Remove subtask
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-subtask')) {
            e.target.closest('.subtask-row').remove();
        }
    });
});
</script>
@endsection
