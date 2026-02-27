<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    protected $fillable = [
        'name',
        'head_office_address',
        'email',
        'phone',
        'address',
    ];

    /**
     * Get the contacts for this partner.
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(PartnerContact::class);
    }

    /**
     * Get the stations for this partner.
     */
    public function stations(): HasMany
    {
        return $this->hasMany(Station::class);
    }
}
