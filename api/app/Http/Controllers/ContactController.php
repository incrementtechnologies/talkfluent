<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\ActiveCampaign;
class ContactController extends TalkController
{
    function __construct(){
      $this->model = new Contact();
    }


    public function create(Request $request){
      $data = $request->all();
      $array = array(
        'email' => $data['email'],
        'first_name'  => $data['fullname']
      );

      new ActiveCampaign($array);
      $this->model = new Contact();
      $this->insertDB($data);
      return $this->response();
    }
}
