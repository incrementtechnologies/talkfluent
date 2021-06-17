<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountPaymentMethod;
class AccountPaymentMethodController extends TalkController
{

  public $paypalAccountController = 'App\Http\Controllers\PayPalAccountController';
  public $creditCardController = 'App\Http\Controllers\CreditCardController';

  function __construct(){
  	$this->model = new AccountPaymentMethod();
  }

  public function retrieve(Request $request)
  {
    $data = $request->all();

    $this->model = new AccountPaymentMethod();
    $this->retrieveDB($data);

    $result = $this->response['data'];
    if (sizeof($result) > 0) {
      for ($i = 0; $i < sizeof($result); $i++) {
        $item = $result[$i];
        if($item['method'] == 'stripe'){
          $this->response['data'][$i]['details'] = app($this->creditCardController)->getByParams('id', $item['source']);
        }else if($item['method'] == 'paypal'){
          $this->response['data'][$i]['details'] = app($this->paypalAccountController)->getByParams('id', $item['source']);
        }
      }
    }
    return $this->response();
  }
}
