<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashBookEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_date',
        'transaction_type',
        'amount',
        'description',
        'reference',
        'balance',
        'related_invoice_id',
        'related_client_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'amount' => 'decimal:2',
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
     * Get the related invoice
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'related_invoice_id');
    }

    /**
     * Get the related client
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Clients::class, 'related_client_id');
    }

    /**
     * Get transaction type label
     */
    public function getTransactionTypeLabelAttribute(): string
    {
        return match($this->transaction_type) {
            'receipt' => 'Receipt',
            'payment' => 'Payment',
            default => 'Unknown',
        };
    }
}
