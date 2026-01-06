<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PettyCashEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_date',
        'transaction_type',
        'amount',
        'description',
        'receiver_beneficiary',
        'category',
        'receipt_number',
        'approved_by',
        'approved_by_user_id',
        'authorization_status',
        'authorized_by_user_id',
        'authorized_at',
        'authorization_notes',
        'employee_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'amount' => 'decimal:2',
        'authorized_at' => 'datetime',
    ];

    /**
     * Get the user who created the entry
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    /**
     * Get the user who authorized
     */
    public function authorizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'authorized_by_user_id');
    }

    /**
     * Get the employee associated with this entry
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }

    /**
     * Get transaction type label
     */
    public function getTransactionTypeLabelAttribute(): string
    {
        return match($this->transaction_type) {
            'expense' => 'Expense',
            'replenishment' => 'Replenishment',
            default => 'Unknown',
        };
    }

    /**
     * Get authorization status label
     */
    public function getAuthorizationStatusLabelAttribute(): string
    {
        return match($this->authorization_status) {
            'pending' => 'Pending',
            'authorized' => 'Authorized',
            'unauthorized' => 'Unauthorized',
            default => 'Unknown',
        };
    }

    /**
     * Get authorization status color
     */
    public function getAuthorizationStatusColorAttribute(): string
    {
        return match($this->authorization_status) {
            'pending' => 'yellow',
            'authorized' => 'green',
            'unauthorized' => 'red',
            default => 'gray',
        };
    }

    /**
     * Check if entry is authorized
     */
    public function isAuthorized(): bool
    {
        return $this->authorization_status === 'authorized';
    }
}
