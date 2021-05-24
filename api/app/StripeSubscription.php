<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeSubscription extends APIModel
{
    protected $table = 'stripe_subscriptions';
    protected $fillable = ['account_id', 'credit_card_id','subscription'];
}
