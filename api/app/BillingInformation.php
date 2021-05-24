<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillingInformation extends APIModel
{
    protected $table = 'billing_informations';
    protected $fillable = ['account_id', 'company', 'address', 'postal_code', 'country', 'state'];
}
