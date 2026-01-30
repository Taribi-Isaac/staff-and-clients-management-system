<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskAttachment;
use App\Models\TaskSubtask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource with statistics.
     */
    public function index(Request $request)
    {
        $query = $request->input('search');
        $status = $request->input('status');
        $priority = $request->input('priority');
        $department = $request->input('department');
        $assignedTo = $request->input('assigned_to');
        $createdBy = $request->input('created_by');

        // Get statistics
        $stats = $this->getStatistics();

        // Build query
        $tasks = Task::with(['creator', 'assignedUsers', 'subtasks'])
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($priority, function ($q) use ($priority) {
                $q->where('priority', $priority);
            })
            ->when($department, function ($q) use ($department) {
                $q->where('department', $department);
            })
            ->when($assignedTo, function ($q) use ($assignedTo) {
                $q->assignedTo($assignedTo);
            })
            ->when($createdBy, function ($q) use ($createdBy) {
                $q->createdBy($createdBy);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get users for filters
        $users = User::all();

        return view('tasks.index', compact('tasks', 'stats', 'users'));
    }

    /**
     * Get task statistics
     */
    private function getStatistics()
    {
        $userId = auth()->id();

        return [
            'total_tasks' => Task::count(),
            'pending_tasks' => Task::pending()->count(),
            'in_progress_tasks' => Task::inProgress()->count(),
            'completed_tasks' => Task::completed()->count(),
            'overdue_tasks' => Task::overdue()->count(),
            'cancelled_tasks' => Task::where('status', 'cancelled')->count(),
            'user_created_tasks' => Task::createdBy($userId)->count(),
            'user_assigned_tasks' => Task::assignedTo($userId)->count(),
            'user_completed_tasks' => Task::assignedTo($userId)->completed()->count(),
            'top_creator' => $this->getTopCreator(),
            'top_performer' => $this->getTopPerformer(),
            'completion_rate' => $this->getCompletionRate($userId),
            'average_completion_time' => $this->getAverageCompletionTime(),
            'tasks_by_department' => $this->getTasksByDepartment(),
        ];
    }

    private function getTopCreator()
    {
        return User::withCount('createdTasks')
            ->orderBy('created_tasks_count', 'desc')
            ->first();
    }

    private function getTopPerformer()
    {
        return User::withCount(['assignedTasks' => function ($query) {
            $query->where('status', 'completed');
        }])
        ->orderBy('assigned_tasks_count', 'desc')
        ->first();
    }

    private function getCompletionRate($userId)
    {
        $assignedTasks = Task::assignedTo($userId)->count();
        if ($assignedTasks === 0) {
            return 0;
        }
        $completedTasks = Task::assignedTo($userId)->completed()->count();
        return round(($completedTasks / $assignedTasks) * 100, 2);
    }

    private function getAverageCompletionTime()
    {
        $completedTasks = Task::completed()
            ->whereNotNull('completed_at')
            ->whereNotNull('created_at')
            ->get();

        if ($completedTasks->isEmpty()) {
            return 0;
        }

        $totalDays = $completedTasks->sum(function ($task) {
            return $task->created_at->diffInDays($task->completed_at);
        });

        return round($totalDays / $completedTasks->count(), 2);
    }

    private function getTasksByDepartment()
    {
        return Task::select('department', DB::raw('count(*) as count'))
            ->whereNotNull('department')
            ->groupBy('department')
            ->get()
            ->pluck('count', 'department');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $departments = ['Rodnav', 'Solar Installation', 'Networking', 'Logistics', 'General'];
        
        return view('tasks.create', compact('users', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:pending,in_progress,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'department' => 'nullable|string|max:255',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
            'subtasks' => 'nullable|array',
            'subtasks.*.title' => 'required|string|max:255',
            'subtasks.*.description' => 'nullable|string',
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_date' => $validated['start_date'],
            'due_date' => $validated['due_date'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'department' => $validated['department'] ?? null,
            'created_by' => auth()->id(),
        ]);

        // Assign users
        if (isset($validated['assigned_users'])) {
            $task->assignedUsers()->attach($validated['assigned_users']);
        }

        // Create subtasks
        if (isset($validated['subtasks'])) {
            foreach ($validated['subtasks'] as $index => $subtask) {
                TaskSubtask::create([
                    'task_id' => $task->id,
                    'title' => $subtask['title'],
                    'description' => $subtask['description'] ?? null,
                    'order' => $index,
                ]);
            }
        }

        // Log activity
        $task->logActivity('created', 'Task created');

        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $task = Task::with([
            'creator',
            'assignedUsers',
            'comments.user',
            'attachments.uploader',
            'subtasks',
            'activityLogs.user'
        ])->findOrFail($id);

        $users = User::all();

        return view('tasks.show', compact('task', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     * Only the creator or assigned users can edit tasks.
     */
    public function edit($id)
    {
        $task = Task::with(['assignedUsers', 'subtasks'])->findOrFail($id);

        // Check if user can edit (creator or assigned user)
        if (!$task->canEdit()) {
            return redirect()->route('tasks.show', $task->id)
                ->with('error', 'You can only edit tasks you created or are assigned to.');
        }

        $users = User::all();
        $departments = ['Rodnav', 'Solar Installation', 'Networking', 'Logistics', 'General'];

        return view('tasks.edit', compact('task', 'users', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     * Only the creator or assigned users can edit tasks.
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Check if user can edit (creator or assigned user)
        if (!$task->canEdit()) {
            return redirect()->route('tasks.show', $task->id)
                ->with('error', 'You can only edit tasks you created or are assigned to.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:pending,in_progress,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'department' => 'nullable|string|max:255',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ]);

        $oldStatus = $task->status;

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_date' => $validated['start_date'],
            'due_date' => $validated['due_date'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'department' => $validated['department'] ?? null,
            'completed_at' => $validated['status'] === 'completed' ? now() : null,
        ]);

        // Update assigned users
        if (isset($validated['assigned_users'])) {
            $task->assignedUsers()->sync($validated['assigned_users']);
        }

        // Log status change
        if ($oldStatus !== $validated['status']) {
            $task->logActivity('status_changed', 'Status changed from ' . $oldStatus . ' to ' . $validated['status']);
        }

        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     * Only the task creator can delete tasks at any time.
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        // Only the creator can delete tasks
        if ($task->created_by !== auth()->id()) {
            return redirect()->route('tasks.index')
                ->with('error', 'You can only delete tasks you created.');
        }

        // Delete attachments
        foreach ($task->attachments as $attachment) {
            if ($attachment->attachment_type !== 'link' && Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }

    /**
     * Add comment to task
     */
    public function addComment(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'comment' => 'required|string',
            'mentioned_users' => 'nullable|array',
            'mentioned_users.*' => 'exists:users,id',
        ]);

        $comment = TaskComment::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'comment' => $validated['comment'],
            'mentioned_users' => $validated['mentioned_users'] ?? [],
        ]);

        // Log activity
        $task->logActivity('commented', 'Comment added');

        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Comment added successfully!');
    }

    /**
     * Add attachment to task
     */
    public function addAttachment(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'file' => 'nullable|file|max:10240',
            'url' => 'nullable|url',
            'attachment_type' => 'required|in:file,image,link',
        ]);

        if ($validated['attachment_type'] === 'link') {
            TaskAttachment::create([
                'task_id' => $task->id,
                'uploaded_by' => auth()->id(),
                'file_name' => parse_url($validated['url'], PHP_URL_HOST),
                'file_path' => '',
                'url' => $validated['url'],
                'attachment_type' => 'link',
            ]);
        } else {
            $file = $request->file('file');
            $filePath = $file->store('task-attachments', 'public');

            TaskAttachment::create([
                'task_id' => $task->id,
                'uploaded_by' => auth()->id(),
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'attachment_type' => $validated['attachment_type'],
            ]);
        }

        // Log activity
        $task->logActivity('attachment_added', 'Attachment added');

        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Attachment added successfully!');
    }

    /**
     * Delete attachment
     */
    public function deleteAttachment($taskId, $attachmentId)
    {
        $attachment = TaskAttachment::findOrFail($attachmentId);
        
        if ($attachment->task_id != $taskId) {
            abort(404);
        }

        if ($attachment->attachment_type !== 'link' && Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return redirect()->route('tasks.show', $taskId)
            ->with('success', 'Attachment deleted successfully!');
    }

    /**
     * Toggle subtask completion
     */
    public function toggleSubtask(Request $request, $taskId, $subtaskId)
    {
        $subtask = TaskSubtask::findOrFail($subtaskId);
        
        if ($subtask->task_id != $taskId) {
            abort(404);
        }

        $subtask->update([
            'is_completed' => !$subtask->is_completed,
            'completed_at' => !$subtask->is_completed ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'is_completed' => $subtask->is_completed,
        ]);
    }

    /**
     * Add subtask
     */
    public function addSubtask(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $order = $task->subtasks()->max('order') ?? 0;

        TaskSubtask::create([
            'task_id' => $task->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'order' => $order + 1,
        ]);

        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Subtask added successfully!');
    }

    /**
     * Delete subtask
     */
    public function deleteSubtask($taskId, $subtaskId)
    {
        $subtask = TaskSubtask::findOrFail($subtaskId);
        
        if ($subtask->task_id != $taskId) {
            abort(404);
        }

        $subtask->delete();

        return redirect()->route('tasks.show', $taskId)
            ->with('success', 'Subtask deleted successfully!');
    }
}
