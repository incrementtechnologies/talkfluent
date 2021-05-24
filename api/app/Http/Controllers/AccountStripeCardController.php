<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountStripeCard;
class AccountStripeCardController extends TalkController
{
    function __construct(){
      $this->model = new AccountStripeCard();
    }

    public function retrieve(Request $request){
    	$data = $request->all();
    	$this->model = new AccountStripeCard();
    	$this->retrieveDB($data);
    	$result = $this->response['data'];
    	if(sizeof($result) > 0){
    		$i = 0;
    		foreach ($result as $key) {
    			$this->response['data'][$i]['brand_icon'] = $this->getIconBrand($result[$i]['brand']);
    			$i++;
    		}
    		return $this->response();
    	}else{
    		return $this->response();
    	}
    }

    public function getIconBrand($brand){
    	switch ($brand) {
    		case 'Visa':
    			return 'fa-cc-visa';
    		case 'Amex':
    			return 'fa-cc-amex';
    		case 'Dinners Club':
    			return 'fa-cc-dinners-club';
    		case 'JCB':
    			return 'fa-cc-jcb';
    	}
    }
}
