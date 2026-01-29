<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'transaction_type',
        'quantity',
        'assigned_to_user_id',
        'assigned_to_client_id',
        'assigned_to_project_id',
        'assigned_to_external_individual',
        'transaction_date',
        'expected_return_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'expected_return_date' => 'date',
        'quantity' => 'integer',
    ];

    /**
     * Get the item
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the user assigned to
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to_user_id');
    }

    /**
     * Get the client assigned to
     */
    public function assignedClient(): BelongsTo
    {
        return $this->belongsTo(Clients::class, 'assigned_to_client_id');
    }

    /**
     * Get the project assigned to
     */
    public function assignedProject(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'assigned_to_project_id');
    }

    /**
     * Get the user who created the transaction
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Get transaction type label
     */
    public function getTransactionTypeLabelAttribute(): string
    {
        return match($this->transaction_type) {
            'purchase' => 'Purchase',
            'assignment' => 'Assignment',
            'return' => 'Return',
            'adjustment' => 'Adjustment',
            'consumption' => 'Consumption',
            default => 'Unknown',
        };
    }
}
