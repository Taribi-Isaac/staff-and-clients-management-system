<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'type',
        'title',
        'client_id',
        'client_name',
        'client_email',
        'client_address',
        'invoice_date',
        'due_date',
        'notes',
        'terms',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'discount',
        'total',
        'status',
        'created_by',
        // Recurring fields
        'is_recurring',
        'recurring_frequency',
        'recurring_start_date',
        'recurring_end_date',
        'next_recurring_date',
        'parent_invoice_id',
        'notification_days_before',
        'last_notification_sent_at',
        'is_recurring_paused',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        // Recurring casts
        'is_recurring' => 'boolean',
        'recurring_start_date' => 'date',
        'recurring_end_date' => 'date',
        'next_recurring_date' => 'date',
        'last_notification_sent_at' => 'datetime',
        'is_recurring_paused' => 'boolean',
        'notification_days_before' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('order');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Clients::class, 'client_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Recurring invoice relationships
    public function parentInvoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'parent_invoice_id');
    }

    public function childInvoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'parent_invoice_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(RecurringInvoiceNotification::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = static::generateInvoiceNumber($invoice->type ?? 'invoice');
            }
        });
    }

    public static function generateInvoiceNumber(string $type = 'invoice'): string
    {
        $prefixes = [
            'invoice' => 'INV-',
            'receipt' => 'RCP-',
            'quote' => 'QUO-',
        ];
        
        $prefix = $prefixes[$type] ?? 'INV-';
        $year = date('Y');
        
        $lastInvoice = static::where('invoice_number', 'like', $prefix . $year . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Recurring invoice helper methods
    public function isRecurring(): bool
    {
        return $this->is_recurring && !$this->is_recurring_paused;
    }

    public function getNextRecurringDate(): ?\Carbon\Carbon
    {
        if (!$this->is_recurring || !$this->due_date) {
            return null;
        }

        $nextDate = $this->next_recurring_date ?? $this->due_date;

        switch ($this->recurring_frequency) {
            case 'weekly':
                return $nextDate->copy()->addWeek();
            case 'biweekly':
                return $nextDate->copy()->addWeeks(2);
            case 'monthly':
                return $nextDate->copy()->addMonth();
            case 'quarterly':
                return $nextDate->copy()->addMonths(3);
            case 'yearly':
                return $nextDate->copy()->addYear();
            default:
                return null;
        }
    }

    public function shouldSendNotification(): bool
    {
        if (!$this->isRecurring() || !$this->due_date) {
            return false;
        }

        $daysBefore = $this->notification_days_before ?? 3;
        $notificationDate = $this->due_date->copy()->subDays($daysBefore);

        // Check if today is the notification date or past it
        $shouldSend = now()->startOfDay()->greaterThanOrEqualTo($notificationDate->startOfDay());

        // Check if notification was already sent today
        if ($shouldSend && $this->last_notification_sent_at) {
            $lastSentDate = $this->last_notification_sent_at->startOfDay();
            $today = now()->startOfDay();
            if ($lastSentDate->equalTo($today)) {
                return false; // Already sent today
            }
        }

        return $shouldSend;
    }

    public function canGenerateNext(): bool
    {
        if (!$this->isRecurring() || $this->status !== 'paid') {
            return false;
        }

        // Check if there's an end date and if we've passed it
        if ($this->recurring_end_date && now()->startOfDay()->greaterThan($this->recurring_end_date->startOfDay())) {
            return false;
        }

        // Check if next recurring date is set and we should generate
        if ($this->next_recurring_date) {
            return now()->startOfDay()->greaterThanOrEqualTo($this->next_recurring_date->startOfDay());
        }

        // If no next_recurring_date, calculate it
        $nextDate = $this->getNextRecurringDate();
        if ($nextDate) {
            return now()->startOfDay()->greaterThanOrEqualTo($nextDate->startOfDay());
        }

        return false;
    }
}
