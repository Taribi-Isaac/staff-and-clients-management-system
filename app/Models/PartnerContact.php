<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerContact extends Model
{
    protected $fillable = [
        'partner_id',
        'name',
        'phone',
        'email',
    ];

    /**
     * Get the partner that owns this contact.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
