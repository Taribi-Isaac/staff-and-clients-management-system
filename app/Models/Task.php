<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'due_date',
        'status',
        'priority',
        'department',
        'created_by',
        'completed_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_assignments')
            ->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class)->orderBy('created_at', 'desc');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class)->orderBy('created_at', 'desc');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(TaskSubtask::class)->orderBy('order');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(TaskActivityLog::class)->orderBy('created_at', 'desc');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled');
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->whereHas('assignedUsers', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        });
    }

    public function scopeCreatedBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    // Helper methods
    public function isOverdue(): bool
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               !in_array($this->status, ['completed', 'cancelled']);
    }

    public function isAssignedTo($userId): bool
    {
        return $this->assignedUsers()->where('users.id', $userId)->exists();
    }

    /**
     * Check if a user can edit this task
     * Only the creator or assigned users can edit
     */
    public function canEdit($userId = null): bool
    {
        $userId = $userId ?? auth()->id();
        
        if (!$userId) {
            return false;
        }

        // Creator can always edit
        if ($this->created_by === $userId) {
            return true;
        }

        // Check if relationship is loaded (more efficient)
        if ($this->relationLoaded('assignedUsers')) {
            return $this->assignedUsers->contains('id', $userId);
        }

        // Fallback to database query if relationship not loaded
        return $this->isAssignedTo($userId);
    }

    public function getCompletionRate(): float
    {
        $totalSubtasks = $this->subtasks()->count();
        if ($totalSubtasks === 0) {
            return $this->status === 'completed' ? 100 : 0;
        }
        $completedSubtasks = $this->subtasks()->where('is_completed', true)->count();
        return ($completedSubtasks / $totalSubtasks) * 100;
    }

    // Boot method to log activities
    protected static function boot()
    {
        parent::boot();

        static::created(function ($task) {
            $task->logActivity('created', 'Task created', null, $task->toArray());
        });

        static::updated(function ($task) {
            $changes = $task->getChanges();
            if (isset($changes['status'])) {
                $task->logActivity('status_changed', 'Status changed', 
                    ['status' => $task->getOriginal('status')], 
                    ['status' => $changes['status']]);
            } else {
                $task->logActivity('updated', 'Task updated', 
                    array_intersect_key($task->getOriginal(), $changes), 
                    $changes);
            }
        });
    }

    public function logActivity($action, $description = null, $oldValues = null, $newValues = null)
    {
        return $this->activityLogs()->create([
            'user_id' => auth()->id() ?? $this->created_by,
            'action' => $action,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }
}
