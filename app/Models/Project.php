<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'project_type',
        'description',
        'client_id',
        'client_name',
        'client_phone',
        'client_email',
        'status',
        'start_date',
        'end_date',
        'budget',
        'actual_cost',
        'location',
        'assigned_to',
        'progress',
        'notes',
        'photos',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'progress' => 'integer',
        'photos' => 'array',
    ];

    /**
     * Get the client that owns the project.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Clients::class, 'client_id');
    }

    /**
     * Get the user assigned to the project.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }

    /**
     * Get the user who created the project.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Get project type label
     */
    public function getProjectTypeLabelAttribute(): string
    {
        return match($this->project_type) {
            'construction' => 'Construction',
            'internet_installation' => 'Internet Installation',
            'networking' => 'Networking',
            'solar_installation' => 'Solar Installation',
            'maintenance' => 'Maintenance',
            'consultation' => 'Consultation',
            'other' => 'Other',
            default => 'Unknown',
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'planning' => 'bg-gray-500',
            'in_progress' => 'bg-blue-500',
            'on_hold' => 'bg-yellow-500',
            'completed' => 'bg-green-500',
            'cancelled' => 'bg-red-500',
            default => 'bg-gray-500',
        };
    }

    /**
     * Get photo URLs
     */
    public function getPhotoUrlsAttribute(): array
    {
        if (!$this->photos) {
            return [];
        }
        
        return array_map(function ($photo) {
            return asset('storage/' . $photo);
        }, $this->photos);
    }
}
