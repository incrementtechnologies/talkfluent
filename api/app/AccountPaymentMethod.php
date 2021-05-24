<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountPaymentMethod extends APIModel
{
    protected $table = "account_payment_methods";
    protected $fillable = ['account_id', 'method', 'source'];
}
