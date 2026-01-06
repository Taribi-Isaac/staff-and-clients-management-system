<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollBookEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_date',
        'pay_period_start',
        'pay_period_end',
        'employee_id',
        'employee_name',
        'basic_salary',
        'allowances',
        'deductions',
        'net_pay',
        'payment_method',
        'bank_name',
        'account_number',
        'payment_status',
        'payment_date',
        'description',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'pay_period_start' => 'date',
        'pay_period_end' => 'date',
        'payment_date' => 'date',
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
    ];

    /**
     * Get the user who created the entry
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the related employee
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }

    /**
     * Get payment status label
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
            default => 'Unknown',
        };
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'bank_transfer' => 'Bank Transfer',
            'cash' => 'Cash',
            'cheque' => 'Cheque',
            default => 'Unknown',
        };
    }
}
