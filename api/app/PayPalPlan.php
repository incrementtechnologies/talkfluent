<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayPalPlan extends APIModel
{
    protected $table = 'paypal_plans';
    protected $fillable = ['plan', 'name',  'description'];
}
