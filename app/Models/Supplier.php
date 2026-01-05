<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'contact_person',
        'email',
        'phone',
        'address',
    ];

    /**
     * Get items from this supplier
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
