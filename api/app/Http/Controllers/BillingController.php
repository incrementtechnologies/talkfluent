<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Billing;
use App\CreditCard;
use App\Coupon;
use App\PayPalAccount;
use App\AccountPaymentMethod;
class BillingController extends TalkController
{
    function __construct(){
      $this->model = new Billing();
    }

    public function retrieve(Request $request){
    	$data = $request->all();
    	$this->model = new Billing();
        $billingData = $data;
        $billingData['sort'] = array('created_at' => 'DESC');
    	$this->retrieveDB($billingData);
    	$result = $this->response['data'];
        // retrieve paypal agreement
        // retrieve payment method
        // retrieve billing history
        // retrieve stripe cards
        $finalResult = array();
    	if(sizeof($result) > 0){
    		$i = 0;
    		foreach ($result as $key) {
                if($result[$i]['payment_method'] == 'credit_card'){
                    $stripeCards = CreditCard::where('account_id', '=', $result[$i]['account_id'])->get();
                    if(sizeof($stripeCards) > 0){
                        $stripeCards[0]['brand_icon'] = $this->getIconBrand($stripeCards[0]['brand']);
                        $this->response['data'][$i]['stripe'] = $stripeCards[0];
                    }else{
                        $this->response['data'][$i]['stripe'] = null;
                    }
                }else{
                    $this->response['data'][$i]['stripe'] = null;
                }
                $this->response['data'][$i]['coupon'] = ($result[$i]['coupon_id'] !== null) ? $this->getCoupon($result[$i]['coupon_id']) : null;
                $this->response['data'][$i]['start_date_human'] = Carbon::createFromFormat('Y-m-d H:i:s', $result[$i]['start_date'])->copy()->tz('Asia/Manila')->format('F j, Y');
                $this->response['data'][$i]['end_date_human'] = Carbon::createFromFormat('Y-m-d H:i:s', $result[$i]['end_date'])->copy()->tz('Asia/Manila')->format('F j, Y');
    			$i++;
    		}
    	}
    	$finalResult['billing'] = (sizeof($this->response['data']) > 0) ? $this->response['data'] : null;

        $this->model = new CreditCard();
        $this->retrieveDB($data);
        $result = $this->response['data'];
        if(sizeof($result) > 0){
            $i = 0;
            foreach ($result as $key) {
                $this->response['data'][$i]['brand_icon'] = $this->getIconBrand($result[$i]['brand']);
                $i++;
            }
            $finalResult['credit_card'] = $this->response['data'];
        }else{
            $finalResult['credit_card'] = null;
        }

        $this->model = new PayPalAccount();
        $this->retrieveDB($data);
        $finalResult['paypal'] = (sizeof($this->response['data']) > 0) ? $this->response['data'] : null;

        $this->model = new AccountPaymentMethod();
        $this->retrieveDB($data);

        $finalResult['payment_method'] = (sizeof($this->response['data']) > 0) ? $this->response['data'][0] : null;
        return response()->json($finalResult);
    }

    public function getCoupon($couponId){
        $result = Coupon::where('id', '=', $couponId)->get();
        return (sizeof($result) > 0) ? $result[0] : null;
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
