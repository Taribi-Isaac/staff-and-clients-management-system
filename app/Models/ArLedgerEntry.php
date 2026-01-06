<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArLedgerEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_date',
        'client_id',
        'client_name',
        'invoice_id',
        'invoice_number',
        'amount',
        'due_date',
        'status',
        'paid_amount',
        'balance',
        'last_payment_date',
        'description',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'due_date' => 'date',
        'last_payment_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    /**
     * Get the user who created the entry
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the related client
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Clients::class, 'client_id');
    }

    /**
     * Get the related invoice
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'partial' => 'Partial',
            'paid' => 'Paid',
            'overdue' => 'Overdue',
            'written_off' => 'Written Off',
            default => 'Unknown',
        };
    }

    /**
     * Check if entry is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'overdue' || ($this->status === 'pending' && $this->due_date < now() && $this->balance > 0);
    }
}
