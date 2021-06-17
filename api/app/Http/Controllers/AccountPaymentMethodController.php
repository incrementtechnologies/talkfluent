<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountPaymentMethod;
use App\CreditCard;
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
    $response = [];
    if (sizeof($result) > 0) {
      for ($i = 0; $i < sizeof($result); $i++) {
        $item = $result[$i];
        $details = null;
        if($item['method'] == 'stripe'){
          $details = app($this->creditCardController)->getByParams('id', $item['source']);
        }else if($item['method'] == 'paypal'){
          $details = app($this->paypalAccountController)->getByParams('id', $item['source']);
        }

        if($details != null){
          $item['details'] = $details;
          $item['type'] = 'existing';
          $response[] = $item;
        }
      }
    }
    $this->response['data'] = $response;
    return $this->response();
  }

  public function update(Request $request){
    $data = $request->all();
    $accountId = $data['account_id'];

    CreditCard::where('account_id', '=', $accountId)->update(array('status' => 'inactive'));
    AccountPaymentMethod::where('account_id', '=', $accountId)->update(array('status' => 'inactive'));

    $this->model = new AccountPaymentMethod();
    $this->updateDB(array(
      'id' => $data['id']
    ));

    return $this->response();
  }

}
