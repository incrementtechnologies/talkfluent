<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends APIModel
{
    protected $table = 'coupons';
    protected $fillable = ['code', 'type', 'start_datetime', 'expiry_datetime'];
}
