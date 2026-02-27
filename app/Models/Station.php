<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Station extends Model
{
    protected $fillable = [
        'partner_id',
        'name',
        'address',
        'state',
        'location',
        'assets',
        'primary_contact_name',
        'primary_contact_email',
        'primary_contact_phone',
    ];

    /**
     * Get the partner that owns this station.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Get the employees assigned to this station.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employees::class);
    }
}
