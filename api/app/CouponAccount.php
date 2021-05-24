<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponAccount extends APIModel
{
    protected $table = 'coupon_accounts';
    protected $fillable = ['account_id', 'coupon_id'];
}
