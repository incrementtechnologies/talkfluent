<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CouponAccount;
class CouponAccountController extends TalkController
{
    function __construct(){
      $this->model = new CouponAccount();
    }
}
