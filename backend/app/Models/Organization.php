<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    // Explicitly defining the table name (since it's not the default "organizations")
    protected $table = 'signed_up_organizations';

    // Defining the attributes that can be mass assigned
    protected $fillable = [
        'date_signed_up',
        'company_name',
        'contact_email',
        'contact_phone',
        'monthly_plan',
        'manager_name',
        'manager_phone',
        'website',
        'address',
        'address2',
        'state',
        'city',
        'country',
        'zip_code',
    ];

    // Optionally, specify the attributes that should be cast to a particular type
    protected $casts = [
        'date_signed_up' => 'date',
    ];
}
