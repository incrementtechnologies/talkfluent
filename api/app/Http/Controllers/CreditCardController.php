<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CreditCard;
class CreditCardController extends TalkController
{
    function __construct(){
      $this->model = new CreditCard();
    }
}
