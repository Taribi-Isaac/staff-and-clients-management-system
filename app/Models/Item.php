<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'item_code',
        'category_id',
        'supplier_id',
        'description',
        'unit',
        'quantity',
        'min_stock_level',
        'unit_price',
        'purchase_date',
        'location',
        'status',
        'is_consumable',
        'created_by',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
        'min_stock_level' => 'integer',
        'is_consumable' => 'boolean',
    ];

    /**
     * Get the category that owns the item
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the supplier that owns the item
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the user who created the item
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Get inventory transactions for this item
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'available' => 'bg-green-500',
            'low_stock' => 'bg-yellow-500',
            'out_of_stock' => 'bg-red-500',
            'discontinued' => 'bg-gray-500',
            default => 'bg-gray-500',
        };
    }

    /**
     * Update stock status based on quantity
     */
    public function updateStockStatus(): void
    {
        if ($this->quantity <= 0) {
            $this->status = 'out_of_stock';
        } elseif ($this->quantity <= $this->min_stock_level) {
            $this->status = 'low_stock';
        } else {
            $this->status = 'available';
        }
        $this->save();
    }
}
