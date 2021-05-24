<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Billing extends APIModel
{
    protected $table = 'billings';
    protected $fillale = ['account_id', 'start_date', 'end_date', 'coupon_id', 'payment_method', 'currency', 'number_of_months', 'amount_per_month', 'total_amount', 'taxes_and_fees', 'discount_amount'];
    protected $dates = ['start_date', 'end_date', 'created_at'];
    public function getCreatedAtAttribute($date)
    {
      return Carbon::createFromFormat('Y-m-d H:i:s', $date)->copy()->tz('Asia/Manila')->format('F j, Y');
    }

}