<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayPalAgreement extends APIModel
{
    protected $table = 'paypal_agreements';
    protected $fillable = ['account_id', 'name', 'agreement', 'name','description', 'start_date', 'token'];
}
