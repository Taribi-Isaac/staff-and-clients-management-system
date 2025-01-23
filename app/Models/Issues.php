<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issues extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'issues';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'issue_description',
        'client_name',
        'kit_number',
        'date',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'date' => 'date',
        'status' => 'string',
    ];

   

    /**
     * Scope a query to only include issues with a specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the available status options.
     */
    public static function getStatusOptions()
    {
        return ['received', 'in-progress', 'resolved'];
    }
}
