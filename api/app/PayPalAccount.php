<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class PayPalAccount extends APIModel
{
    protected $table = 'paypal_accounts';
    protected $fillable = ['account_id', 'name'];
}
