<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends APIModel
{
    protected $table = 'accounts';
    protected $hidden = array('password');

    protected $fillable = ['code', 'email', 'username', 'status', 'password', 'plan', 'payment_status', 'canceled_on', 'paused_on'];
}