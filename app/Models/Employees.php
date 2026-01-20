<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employees extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'start_date',
        'end_date',
        'role',
        'status',
        'employment_type',
        'passport',
        'state_of_origin',
        'local_government_area',
        'home_town',
        'residential_address',
        'guarantor_1_name',
        'guarantor_1_email',
        'guarantor_1_phone',
        'guarantor_1_address',
        'guarantor_2_name',
        'guarantor_2_email',
        'guarantor_2_phone',
        'guarantor_2_address',
        'bank_name',
        'account_number',
        'account_name',
        'submit_doc_1',
        'submit_doc_2',
        'submit_doc_3',
    ];

    public function pettyCashEntries(): HasMany
    {
        return $this->hasMany(PettyCashEntry::class, 'employee_id');
    }

    public function payrollBookEntries(): HasMany
    {
        return $this->hasMany(PayrollBookEntry::class, 'employee_id');
    }
}
