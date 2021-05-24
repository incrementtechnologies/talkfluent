<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BillingInformation;
use Carbon\Carbon;
class BillingInformationController extends TalkController
{
    function __construct(){
      $this->model = new BillingInformation();
      $this->notRequired = array(
      	'company'
      );
    }

    public function update(Request $request){
    	$data = $request->all();

    	BillingInformation::where('id', '=', $data['id'])->update(array(
    		'company'	=> ($data['company']) ? $data['company'] : null,
    		'address'	=> $data['address'],
    		'city'		=> $data['city'],
    		'postal_code'	=> $data['postal_code'],
    		'state'		=> $data['state'],
    		'updated_at'	=> Carbon::now()
    	));

    	return response()->json(array(
    		'data'	=> true,
    		'error'	=> null,
    		'timestamps'	=> Carbon::now()
    	));
    }
}
