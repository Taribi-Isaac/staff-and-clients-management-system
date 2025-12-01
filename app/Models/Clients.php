<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
