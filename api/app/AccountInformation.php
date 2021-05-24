<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountInformation extends APIModel
{

  protected $table = 'account_informations';
  protected $fillable = ['account_id', 'first_name', 'last_name', 'middle_name', 'sex', 'birth_date', 'address', 'cellular_number'];
}
