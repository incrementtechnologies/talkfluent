<?php

namespace App\Http\Controllers;

use App\AccountInformation;
use Illuminate\Http\Request;
use Carbon\Carbon;
class AccountInformationController extends TalkController
{
     function __construct(){  
        $this->model = new AccountInformation();
        $this->notRequired = array(
          'middle_name', 'cellular_number', 'address', 'birth_date'
        );
    }

    public function update(Request $request){
    	$data = $request->all();

    	AccountInformation::where('id', '=', $data['id'])->update(array(
    		'first_name'	=> $data['first_name'],
    		'middle_name'	=> ($data['middle_name']) ? $data['middle_name'] : null,
    		'last_name'		=> $data['last_name'],
    		'birth_date'	=> ($data['birth_date']) ? $data['birth_date'] : null,
    		'sex'					=> $data['sex'],
    		'cellular_number'	=> ($data['cellular_number']) ? $data['cellular_number'] : null,
    		'address'			=> ($data['address']) ? $data['address'] : null,
    		'updated_at'	=>  Carbon::now()
    	));

    	return response()->json(array(
    		'data'	=> true,
    		'error'	=> null,
    		'timestamps' => Carbon::now()
    	));
    }
}
