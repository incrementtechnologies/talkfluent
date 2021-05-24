<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountPaymentMethod;
class AccountPaymentMethodController extends TalkController
{
    function __construct(){
    	$this->model = new AccountPaymentMethod();
    }
}
