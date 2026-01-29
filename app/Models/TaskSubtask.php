<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskSubtask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'title',
        'description',
        'is_completed',
        'completed_at',
        'order',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($subtask) {
            if ($subtask->isDirty('is_completed') && $subtask->is_completed) {
                $subtask->completed_at = now();
            } elseif ($subtask->isDirty('is_completed') && !$subtask->is_completed) {
                $subtask->completed_at = null;
            }
        });
    }
}
