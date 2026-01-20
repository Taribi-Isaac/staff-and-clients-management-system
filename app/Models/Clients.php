<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clients extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'client_name',
        'business_name',
        'location',
        'account_number',
        'dish_serial_number',
        'kit_number',
        'starlink_id',
        'subscription_duration',
        'subscription_start_date',
        'subscription_end_date',
        'email',
        'password',
        'phone',
        'service_address',
        'account_name',
        'card_details',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'client_id');
    }

    public function arLedgerEntries(): HasMany
    {
        return $this->hasMany(ArLedgerEntry::class, 'client_id');
    }

    public function salesBookEntries(): HasMany
    {
        return $this->hasMany(SalesBookEntry::class, 'client_id');
    }

    public function cashBookEntries(): HasMany
    {
        return $this->hasMany(CashBookEntry::class, 'related_client_id');
    }

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class, 'assigned_to_client_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'client_id');
    }
}
