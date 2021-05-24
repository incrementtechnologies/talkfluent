<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends APIModel
{
    protected $table = 'subscriptions';
    protected $fillable = ['email', 'name', 'status'];
}
