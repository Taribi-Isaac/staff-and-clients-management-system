@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 pb-4">
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

    <!-- Task Header -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $task->title }}</h1>
                <div class="flex gap-2 items-center">
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'in_progress' => 'bg-blue-100 text-blue-800',
                            'on_hold' => 'bg-gray-100 text-gray-800',
                            'completed' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                        $priorityColors = [
                            'low' => 'bg-green-100 text-green-800',
                            'medium' => 'bg-yellow-100 text-yellow-800',
                            'high' => 'bg-orange-100 text-orange-800',
                            'urgent' => 'bg-red-100 text-red-800',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded text-sm font-semibold {{ $statusColors[$task->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                    <span class="px-3 py-1 rounded text-sm font-semibold {{ $priorityColors[$task->priority] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($task->priority) }} Priority
                    </span>
                    @if($task->isOverdue())
                        <span class="px-3 py-1 rounded text-sm font-semibold bg-red-100 text-red-800">OVERDUE</span>
                    @endif
                </div>
            </div>
            <div class="flex gap-2">
                @if($task->canEdit())
                    <a href="{{ route('tasks.edit', $task->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 transition">Edit</a>
                @endif
                @if($task->created_by === auth()->id())
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this task? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">Delete</button>
                    </form>
                @endif
                <a href="{{ route('tasks.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">Back to List</a>
            </div>
        </div>

        <!-- Task Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <p class="text-sm text-gray-600">Created by</p>
                <p class="font-semibold">{{ $task->creator->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Department</p>
                <p class="font-semibold">{{ $task->department ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Start Date</p>
                <p class="font-semibold">{{ $task->start_date->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Due Date</p>
                <p class="font-semibold {{ $task->isOverdue() ? 'text-red-600' : '' }}">
                    {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}
                </p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-600">Assigned To</p>
                <div class="flex flex-wrap gap-2 mt-1">
                    @forelse($task->assignedUsers as $user)
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded text-sm">{{ $user->name }}</span>
                    @empty
                        <span class="text-gray-500">Unassigned</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Description -->
        @if($task->description)
        <div class="border-t pt-4 mt-4">
            <h3 class="font-semibold mb-2">Description</h3>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $task->description }}</p>
        </div>
        @endif
    </div>

    <!-- Subtasks / Checklist -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Subtasks / Checklist</h2>
            <button type="button" id="addSubtaskBtn" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">Add Subtask</button>
        </div>

        <!-- Add Subtask Form (Hidden by default) -->
        <div id="addSubtaskForm" class="hidden mb-4 p-4 bg-gray-50 rounded-lg">
            <form action="{{ route('tasks.subtasks.store', $task->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-10">
                        <input type="text" name="title" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Subtask title">
                        <textarea name="description" rows="2" class="w-full p-2 mt-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Description (optional)"></textarea>
                    </div>
                    <div class="col-span-2 flex items-start gap-2">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add</button>
                        <button type="button" id="cancelSubtask" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Subtasks List -->
        <div id="subtasksList">
            @forelse($task->subtasks as $subtask)
            <div class="flex items-center gap-4 p-3 border border-gray-200 rounded-lg mb-2">
                <input type="checkbox" 
                       class="subtask-checkbox" 
                       data-subtask-id="{{ $subtask->id }}"
                       {{ $subtask->is_completed ? 'checked' : '' }}
                       onchange="toggleSubtask({{ $task->id }}, {{ $subtask->id }})">
                <div class="flex-1">
                    <p class="{{ $subtask->is_completed ? 'line-through text-gray-500' : 'font-medium' }}">{{ $subtask->title }}</p>
                    @if($subtask->description)
                        <p class="text-sm text-gray-600 mt-1">{{ $subtask->description }}</p>
                    @endif
                    @if($subtask->is_completed && $subtask->completed_at)
                        <p class="text-xs text-gray-500 mt-1">Completed on {{ $subtask->completed_at->format('M d, Y H:i') }}</p>
                    @endif
                </div>
                <form action="{{ route('tasks.subtasks.destroy', [$task->id, $subtask->id]) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600" onclick="return confirm('Delete this subtask?')">Delete</button>
                </form>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No subtasks yet. Click "Add Subtask" to create one.</p>
            @endforelse
        </div>

        @if($task->subtasks->isNotEmpty())
        <div class="mt-4 pt-4 border-t">
            <p class="text-sm text-gray-600">
                Progress: {{ $task->subtasks->where('is_completed', true)->count() }} / {{ $task->subtasks->count() }} completed 
                ({{ round($task->getCompletionRate(), 1) }}%)
            </p>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $task->getCompletionRate() }}%"></div>
            </div>
        </div>
        @endif
    </div>

    <!-- Attachments -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Attachments</h2>
            <button type="button" id="addAttachmentBtn" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">Add Attachment</button>
        </div>

        <!-- Add Attachment Form (Hidden by default) -->
        <div id="addAttachmentForm" class="hidden mb-4 p-4 bg-gray-50 rounded-lg">
            <form action="{{ route('tasks.attachments.store', $task->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Attachment Type</label>
                    <select name="attachment_type" id="attachmentType" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" onchange="toggleAttachmentInput()">
                        <option value="file">File Upload</option>
                        <option value="image">Image Upload</option>
                        <option value="link">External Link</option>
                    </select>
                </div>
                <div id="fileInput" class="mb-4">
                    <input type="file" name="file" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div id="linkInput" class="mb-4 hidden">
                    <input type="url" name="url" placeholder="https://example.com" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Upload</button>
                    <button type="button" id="cancelAttachment" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Attachments List -->
        <div class="space-y-2">
            @forelse($task->attachments as $attachment)
            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                <div class="flex items-center gap-3">
                    @if($attachment->attachment_type === 'link')
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        <a href="{{ $attachment->url }}" target="_blank" class="text-blue-600 hover:underline">{{ $attachment->file_name }}</a>
                    @else
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <a href="{{ $attachment->file_url }}" target="_blank" class="text-blue-600 hover:underline">{{ $attachment->file_name }}</a>
                        <span class="text-sm text-gray-500">({{ $attachment->file_size ? number_format($attachment->file_size / 1024, 2) . ' KB' : 'N/A' }})</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">by {{ $attachment->uploader->name }}</span>
                    <form action="{{ route('tasks.attachments.destroy', [$task->id, $attachment->id]) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600" onclick="return confirm('Delete this attachment?')">Delete</button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No attachments yet. Click "Add Attachment" to upload files or add links.</p>
            @endforelse
        </div>
    </div>

    <!-- Comments -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-xl font-semibold mb-4">Comments</h2>

        <!-- Add Comment Form -->
        <form action="{{ route('tasks.comments.store', $task->id) }}" method="POST" class="mb-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Add Comment</label>
                <textarea name="comment" rows="4" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Write a comment..."></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Mention Users (Optional)</label>
                <div class="border border-gray-300 rounded-lg p-3 max-h-48 overflow-y-auto bg-gray-50">
                    <div class="space-y-2">
                        @foreach($users as $user)
                            <label class="flex items-center p-2 hover:bg-white rounded cursor-pointer">
                                <input type="checkbox" 
                                       name="mentioned_users[]" 
                                       value="{{ $user->id }}"
                                       class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                <span class="ml-2 text-sm text-gray-700">
                                    <span class="font-medium">{{ $user->name }}</span>
                                    <span class="text-gray-500">({{ $user->email }})</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Tap to select users to mention</p>
            </div>
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">Post Comment</button>
        </form>

        <!-- Comments List -->
        <div class="space-y-4">
            @forelse($task->comments as $comment)
            <div class="border-l-4 border-blue-500 pl-4 py-2">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="font-semibold">{{ $comment->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $comment->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $comment->comment }}</p>
                @if($comment->mentioned_users && count($comment->mentioned_users) > 0)
                    <div class="mt-2">
                        <p class="text-xs text-gray-500">Mentioned: 
                            @foreach($comment->mentioned_users as $mentionedId)
                                @php $mentionedUser = \App\Models\User::find($mentionedId); @endphp
                                @if($mentionedUser)
                                    <span class="font-semibold text-blue-600">@{{ $mentionedUser->name }}</span>
                                @endif
                            @endforeach
                        </p>
                    </div>
                @endif
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No comments yet. Be the first to comment!</p>
            @endforelse
        </div>
    </div>

    <!-- Activity Log -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Activity Log</h2>
        <div class="space-y-3">
            @forelse($task->activityLogs as $log)
            <div class="flex gap-4 pb-3 border-b border-gray-200 last:border-0">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold">{{ substr($log->user->name, 0, 1) }}</span>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="font-semibold">{{ $log->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</p>
                    @if($log->description)
                        <p class="text-sm text-gray-500 mt-1">{{ $log->description }}</p>
                    @endif
                    @if($log->old_values || $log->new_values)
                        <div class="mt-2 text-xs">
                            @if($log->old_values)
                                <span class="text-red-600">- {{ json_encode($log->old_values) }}</span>
                            @endif
                            @if($log->new_values)
                                <span class="text-green-600">+ {{ json_encode($log->new_values) }}</span>
                            @endif
                        </div>
                    @endif
                    <p class="text-xs text-gray-400 mt-1">{{ $log->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No activity logged yet.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
// Subtask management
document.getElementById('addSubtaskBtn').addEventListener('click', function() {
    document.getElementById('addSubtaskForm').classList.remove('hidden');
});

document.getElementById('cancelSubtask').addEventListener('click', function() {
    document.getElementById('addSubtaskForm').classList.add('hidden');
    document.getElementById('addSubtaskForm').querySelector('form').reset();
});

// Attachment management
document.getElementById('addAttachmentBtn').addEventListener('click', function() {
    document.getElementById('addAttachmentForm').classList.remove('hidden');
});

document.getElementById('cancelAttachment').addEventListener('click', function() {
    document.getElementById('addAttachmentForm').classList.add('hidden');
    document.getElementById('addAttachmentForm').querySelector('form').reset();
    toggleAttachmentInput();
});

function toggleAttachmentInput() {
    const type = document.getElementById('attachmentType').value;
    const fileInput = document.getElementById('fileInput');
    const linkInput = document.getElementById('linkInput');
    
    if (type === 'link') {
        fileInput.classList.add('hidden');
        linkInput.classList.remove('hidden');
        linkInput.querySelector('input').required = true;
        fileInput.querySelector('input').required = false;
    } else {
        fileInput.classList.remove('hidden');
        linkInput.classList.add('hidden');
        fileInput.querySelector('input').required = true;
        linkInput.querySelector('input').required = false;
    }
}

// Toggle subtask completion
function toggleSubtask(taskId, subtaskId) {
    fetch(`/tasks/${taskId}/subtasks/${subtaskId}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating subtask');
    });
}
</script>
@endsection
