<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditCard extends APIModel
{
    protected $table = 'credit_cards';
    protected $fillable = ['account_id', 'brand', 'last4', 'exp_year', 'exp_month', 'source', 'customer'];
}
