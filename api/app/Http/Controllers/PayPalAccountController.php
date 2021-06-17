<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PayPalAccount;
class PayPalAccountController extends TalkController
{
  function __construct(){
    $this->model = new PayPalAccount();
  }

  public function getByParams($column, $value){
    $result = PayPalAccount::where($column, $value)->get();
    return sizeof($result) > 0 ? $result[0] : null;
  }
}
