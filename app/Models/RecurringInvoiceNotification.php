<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringInvoiceNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'notification_type',
        'sent_to',
        'sent_at',
        'scheduled_for_date',
        'status',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'scheduled_for_date' => 'date',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
