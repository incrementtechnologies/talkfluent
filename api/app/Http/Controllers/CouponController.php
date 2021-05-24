<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;
use App\StripeWebhooks;
use Carbon\Carbon;
class CouponController extends TalkController
{
    function __construct(){
      $this->model = new Coupon();
    }

    public function retrieve(Request $request){
    	$data = $request->all();
    	$this->model = new Coupon();
        $condition = array(
            'condition' => $data['condition']
        );
    	$this->retrieveDB($condition);
    	$result = $this->response['data'];
    	if(sizeof($result) > 0){
    		$i = 0;
    		$startDate = Carbon::createFromFormat('Y-m-d H:i:s', $result[0]['start_datetime']);
    		$expiryDate = Carbon::createFromFormat('Y-m-d H:i:s', $result[0]['expiry_datetime']);
            
    		$currentDate = Carbon::now();
    		$diffStart = $currentDate->diffInSeconds($startDate, false);
            $diffEnd = $currentDate->diffInSeconds($expiryDate, false);
            if($diffStart < 0 && $diffEnd >= 0){
                $keys = $data['payment_keys'];
                $pk = ($keys['flag'] == false || $keys['flag'] == 'false') ? $keys['stripe']['dev_pk'] : $keys['stripe']['live_pk'];
                $sk = ($keys['flag'] == false  || $keys['flag'] == 'false') ? $keys['stripe']['dev_sk'] : $keys['stripe']['live_sk'];
                $stripe = new StripeWebhooks($pk, $sk);
                $coupon = $stripe->retrieveCoupon($result[0]['code']);
                $this->response['data'][0]['coupon'] = $coupon;
                return $this->response();
            }else{
                return response()->json(array(
                    'data'  => null,
                    'error' => 'Expired Coupon Code',
                    'timestamp' => Carbon::now()
                ));
            }
    	}else{
            return response()->json(array(
                'data'  => null,
                'error' => 'Invalid Coupon Code',
                'timestamp' => Carbon::now()
            ));
        }
    }
}
