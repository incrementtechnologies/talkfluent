<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends APIModel
{
    protected $table    = 'contacts';
    protected $fillable = ['id', 'fullname', 'email', 'subject', 'message']; 
}
