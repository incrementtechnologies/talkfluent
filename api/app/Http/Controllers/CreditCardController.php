<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CreditCard;
class CreditCardController extends TalkController
{
  function __construct(){
    $this->model = new CreditCard();
  }
  
  public function getByParams($column, $value){
    $result = CreditCard::where($column, $value)->get();
    return sizeof($result) > 0 ? $result[0] : null;
  }
}
