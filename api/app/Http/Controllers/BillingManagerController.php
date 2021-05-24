<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\Payment;
use Carbon\Carbon;
class BillingManagerController extends Controller
{

    public function run(Request $request){
    	dispatch(new Payment());
    	return response()->json(
    		array(
    			'data'	=> true,
    			'error'	=> null,
    			'timestamp'	=> Carbon::now()
    		)
    	);
    }
}
