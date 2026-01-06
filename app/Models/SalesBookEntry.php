<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesBookEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_date',
        'invoice_number',
        'invoice_id',
        'client_id',
        'client_name',
        'amount',
        'tax_amount',
        'discount',
        'total',
        'payment_status',
        'paid_amount',
        'payment_method',
        'description',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
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
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    /**
     * Get the related client
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Clients::class, 'client_id');
    }

    /**
     * Get payment status label
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'Pending',
            'partial' => 'Partial',
            'paid' => 'Paid',
            default => 'Unknown',
        };
    }
}
