<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountStripeCard extends APIModel
{
    protected $table = 'stripe_subscriptions';
    protected $fillable = ['account_id', 'credit_card_id','subscription'];
}
