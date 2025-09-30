<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    // Use "code" (a string) as the primary key
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'code';

    protected $fillable = [
        'code',
        'type',
        'percent_off',
        'duration_months',
        'stripe_coupon_id',
    ];
}
