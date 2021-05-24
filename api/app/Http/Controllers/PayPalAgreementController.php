<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PayPalAgreement;
class PayPalAgreementController extends TalkController
{
    function __construct(){
    	$this->model = new PayPalAgreement();
    }
}
