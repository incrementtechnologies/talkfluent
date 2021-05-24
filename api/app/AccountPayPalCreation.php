<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountPayPalCreation extends APIModel
{
    protected $table = 'account_paypal_creations';
    protected $fillable = ['account_id', 'paypal_plan_id', 'method', 'description_amount', 'name', 'client_id', 'address', 'remote_address'];
}
