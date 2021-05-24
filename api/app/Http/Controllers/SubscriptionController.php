<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscription;
use App\ActiveCampaign;

class SubscriptionController extends TalkController
{
  function __construct(){
    $this->model = new Subscription();
    $this->validation = array("email" => "unique:subscriptions");
  }

  public function create(Request $request){
    $data = $request->all();
    $array = array(
      'email' => $data['email']
    );
    new ActiveCampaign($array);
    $this->method = new Subscription();
    $this->insertDB($data);
    return $this->response();
  }
}
