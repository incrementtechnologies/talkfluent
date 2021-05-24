<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PayPalAccount;
class PayPalAccountController extends TalkController
{
    function __construct(){
      $this->model = new PayPalAccount();
    }
}
