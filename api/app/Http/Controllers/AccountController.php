<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\AccountProfilePicture;
use App\AccountInformation;
use App\AccountPaymentMethod;
use App\BillingInformation;
use App\StripeWebhooks;
use App\Billing;
use App\StripeSubscription;
use App\CreditCard;
use App\CouponAccount;
use App\Coupon;
use App\FeedBack;
use App\ActiveCampaign;
use App\Jobs\EmailVerification;
use App\Jobs\EmailForgetPassword;
use App\Jobs\EmailChangedPasswordNotification;
use Carbon\Carbon;
class AccountController extends TalkController
{

    protected $trialPeriod = 7;
    protected $stripe;
    function __construct(){  
        $this->model = new Account();
        $this->validation = array(  
          "email" => "unique:accounts",
          "username"  => "unique:accounts"
        );
        $this->notRequired = array(
          'canceled_on', 'paused_on'
        );
    }

    public function create(Request $request){
     $request = $request->all();
     $account = $request['account'];
     $account['password'] = Hash::make($account['password']);
     $insertData = array(
        'email'     => $account['email'],
        'username'  => $account['username'],
        'password'  => $account['password'],
        'code'      => $this->getCode(),
        'status'    => 'trial',
        'account_type' => "USER",
        'plan'      => $account['plan'],
        'payment_status' => 'created'
     );

     // create customer on stripe
     // charge customer on stripe
     // succeeded then create account
     // else return reasons not to create
    
     $pMethod = $request['payment']['payment_method'];
     $billing = $request['billing'];
     $host = $request['host'];
     if($pMethod == 'credit_card'){

      $coupon = $request['payment']['coupon'];
      if($coupon != null && $coupon != ''){
        $couponResult = Coupon::where('code', '=', $coupon)->get();

        if(sizeof($couponResult) > 0){
          $i = 0;
          $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $couponResult[0]['start_datetime']);
          $expiryDate = Carbon::createFromFormat('Y-m-d H:i:s', $couponResult[0]['expiry_datetime']);
              
          $currentDate = Carbon::now();
          $diffStart = $currentDate->diffInSeconds($startDate, false);
          $diffEnd = $currentDate->diffInSeconds($expiryDate, false);
          if($diffStart < 0 && $diffEnd >= 0){
          }else{
            return response()->json(array(
              'data'  => null,
              "error" => array(
                "status"  => 100,
                "message" => array("coupon" => "Expired Coupon Code")
              ),
              'timestamp' => Carbon::now()
            ));
          }
        }else{
          return response()->json(array(
            'data'  => null,
            "error" => array(
              "status"  => 100,
              "message" => array("coupon" => "Invalid Coupon Code")
            ),
            'timestamp' => Carbon::now()
          ));
        }
      }
      $keys = $request['payment_keys'];
      $products = $request['products'];
      $source = $request['stripe_source'];
      $name = $account['first_name'].' '.$account['last_name'];
      $pk = ($keys['flag'] == false || $keys['flag'] == 'false') ? $keys['stripe']['dev_pk'] : $keys['stripe']['live_pk'];
      $sk = ($keys['flag'] == false  || $keys['flag'] == 'false') ? $keys['stripe']['dev_sk'] : $keys['stripe']['live_sk'];
      $this->stripe = new StripeWebhooks($pk, $sk);
      $customer = $this->stripe->createCustomer($account['email'], $source['id'], $name);
      $charge = $this->stripe->chargeCustomer($account['email'], $source['id'], $customer->id, $this->getPrice($account['plan'], $products['stripe']['plan']), " Talkfluent Subscription Trial Period For US$1"); // Charge $1 USD

      // send email

      if($charge->status == 'succeeded'){
        $this->insertAccount($insertData);
        $accountId = $this->response['data'];
        if($accountId > 0){
          app('App\Http\Controllers\EmailController')->verification($accountId, $host);
          $this->insertAccountInformation($accountId, $account);
          $this->insertBillingInformation($accountId, $billing);
          $this->insertStripeData($accountId, $source, $account, $request['payment'], $products, $charge, $customer);
        }
      }else{
        return response()->json(array(
          "data" => null,
          "error" => array(
            "status"  => 100,
            "message" => array("card" => "Failed to charge your card.")
          ),
          "timestamps"  => Carbon::now()
        ));
      }

      // dispatch(new EmailVerification($insertData, $host));

     }else if($pMethod == 'paypal'){
      $this->model = new Account();
      $this->insertAccount($insertData);
      $accountId = $this->response['data'];
      $config = $request['config'];
      $nickname = $request['paypal']['nickname'];

      if($accountId > 0){
        app('App\Http\Controllers\EmailController')->verification($accountId, $host);
        $this->insertAccountInformation($accountId, $account);
        $this->insertBillingInformation($accountId, $billing);
        // dispatch(new EmailVerification($insertData, $host));
        $returnUrl = app('App\Http\Controllers\PayPalController')->handler($accountId, $account['plan'], $nickname, $config);
        $this->response['paypal_redirect'] = $returnUrl;
      }
     }
      return $this->response();
    }

    public function insertAccount($data){
      new ActiveCampaign(array('email' => $data['email']));
      $this->model = new Account();
      return $this->insertDB($data);
    }

    public function insertAccountInformation($accountId, $account){
      $accountInformationData = array(
        'account_id'  => $accountId,
        'first_name'  => $account['first_name'],
        'last_name'   => $account['last_name'],
        'created_at'  => Carbon::now()
      );
      $accountInformation = new AccountInformation();
      $accountInformation->insert($accountInformationData);
    }

    public function insertStripeData($accountId, $source = null, $account, $payment, $products, $charge, $customer){
      if($payment['payment_method'] == 'credit_card'){
        
        // $customer = $stripe->createCustomer($account['email'], $source['id'], $name);
        // $charge = $stripe->chargeCustomer($account['email'], $source['id'], $customer->id, $this->getPrice($account['plan'], $products['stripe']['plan']), " Talkfluent Subscription Trial Period For $1 USD"); // Charge $1 USD
        $subscription = null;
        if($payment['coupon'] != null){
          $subscription = $this->stripe->createSubscriptionWithCoupon($customer->id, $this->getPlan($account['plan'], $products['stripe']['plan']), $this->trialPeriod, $payment['coupon']);
        }else{
          $subscription = $this->stripe->createSubscription($customer->id, $this->getPlan($account['plan'], $products['stripe']['plan']), $this->trialPeriod);
        }
        $this->insertBilling($accountId, $payment, $account['plan'], $charge);
        $this->insertAccountStripeCard($accountId, $source, $customer->id, $subscription->id);
        // save Account Stripe Cards
      }
    }

    public function getPlan($plan, $stripePlan){
      switch ($plan) {
        case 'monthly':
          return $stripePlan['monthly']['id'];
        case 'annually':
          return $stripePlan['annually']['id'];
        case 'pause': 
          return $stripePlan['pause']['id'];
      }
    }


    public function getPrice($plan, $productPlans){
      switch ($plan) {
        case 'monthly':
          return $productPlans['monthly']['trial_price'];
        case 'annually':
          return $productPlans['annually']['trial_price'];
        case 'semi_annual':
          return $productPlans['semi_annual']['trial_price'];
        case 'pause':
          return $productPlans['pause']['trial_price'];
      }
    }
    
    public function insertBilling($accountId, $payment, $plan, $charge){
        $coupon = null;
        $couponDetails = null;
        $discount = 0;
        $totalAmount = 0;
        $monthly = 49;
        $yearly = 34.5 * 12;
        if($payment['coupon'] !== null){
          $coupon = Coupon::where('code', '=', $payment['coupon'])->first();
          $couponDetails = $coupon;
          if($plan == 'monthly'){
            $discount = ($coupon->type == 'percentage') ? $monthly * (floatval($coupon->value) / 100) : floatval($coupon->value);
            $totalAmount = $monthly - $discount;
          }else{
            $discount = ($coupon->type == 'percentage') ? $yearly * (floatval($coupon->value) / 100) : floatval($coupon->value);
            $totalAmount = $yearly - $discount;
          }
          $coupon = ($coupon) ? $coupon->id: null;
        }else{
          $discount = 0;
          $coupon = null;
          $totalAmount = ($plan == 'monthly') ? $monthly : $yearly;
        }

        $billing = new Billing();
        $billing->account_id = $accountId;
        $billing->status = $charge->status;
        $billing->coupon_id = null;
        $billing->start_date = Carbon::now();
        $billing->end_date = Carbon::now()->addDay(7);
        $billing->payment_method = $payment['payment_method'];
        $billing->currency = "USD";
        $billing->description = 'Trial Period';
        $billing->description_amount = 'US$1.00 for 7 days';
        $billing->total_amount = $payment['total_amount'];
        $billing->taxes_and_fees = $payment['taxes_and_fees'];
        $billing->discount_total_amount = $payment['discount_total_amount'];
        $billing->created_at = Carbon::now();
        $billing->save();

        // details
        // account id
        // send email here

        $billingDetails = array(
          'description' => 'US$1.00 for 7 days Trial',
          'unit_price'  => 1.00,
          'qty'         => 1,
          'amount'      => floatval($payment['total_amount']) / 100,
          'subtotal'    => floatval($payment['total_amount']) / 100,
          'tax'         => $payment['taxes_and_fees'],
          'discount'    => $payment['discount_total_amount'],
          'total'       => floatval($payment['total_amount']) / 100
        );

        $returnUrl = app('App\Http\Controllers\EmailController')->receipt($accountId, null, $billingDetails);

        $billing = new Billing();
        $billing->account_id = $accountId;
        $billing->status = 'created';
        $billing->coupon_id = $coupon;
        $billing->start_date = Carbon::now()->addDay(7);
        $billing->end_date = ($plan == 'monthly') ? Carbon::now()->addMonth(1)->addDay(7) : Carbon::now()->addYear(1)->addDay(7);
        $billing->payment_method = $payment['payment_method'];
        $billing->currency ='USD';
        $billing->description = ($plan == 'monthly') ? 'Monthly Plan' : 'Yearly Plan';
        $billing->description_amount = ($plan == 'monthly') ? 'US$'.$monthly.' per month' : 'US$'.($yearly).' per year';
        $billing->total_amount = $totalAmount * 100;
        $billing->taxes_and_fees = $payment['taxes_and_fees'];
        $billing->discount_total_amount = $discount;
        $billing->created_at = Carbon::now()->addSecond();
        $billing->save();

        Account::where('id', '=', $accountId)->update(
          array(
            'payment_status'  => 'active'
          )
        );

        if($coupon !== null){
          $_coupon_account = new CouponAccount();
          $_coupon_account->account_id = $accountId;
          $_coupon_account->coupon_id = $coupon;
          $_coupon_account->created_at = Carbon::now();
          $_coupon_account->save();
        }

    }

    public function insertBillingInformation($accountId, $info){
      $info['account_id'] = $accountId;
      $info['created_at'] = Carbon::now();
      $billing = new BillingInformation();
      $billing->insert($info);
    }

    public function insertAccountStripeCard($accountId, $source, $customer, $subscription){

      // save credit_cards
      // save stripe_subscriptions
      $_credit_cards = new CreditCard();
      $_credit_cards->account_id = $accountId;
      $_credit_cards->brand = $source['card']['brand'];
      $_credit_cards->last4 = $source['card']['last4'];
      $_credit_cards->exp_year = $source['card']['exp_year'];
      $_credit_cards->exp_month = $source['card']['exp_month'];
      $_credit_cards->source = $source['id'];
      $_credit_cards->customer = $customer;
      $_credit_cards->created_at = Carbon::now();
      $_credit_cards->save();

      if($_credit_cards->id){
        $stripeSubscription = new StripeSubscription();
        $stripeSubscription->account_id = $accountId;
        $stripeSubscription->credit_card_id = $_credit_cards->id;
        $stripeSubscription->subscription = $subscription;
        $stripeSubscription->created_at = Carbon::now();
        $stripeSubscription->save();
      }
      $this->insertAccountPaymentMethod($accountId, 'stripe', $_credit_cards->id);
    }

    public function insertAccountPaymentMethod($accountId, $method, $source){
      $model = new AccountPaymentMethod();
      $model->account_id = $accountId;
      $model->method = $method;
      $model->source = $source;
      $model->created_at = Carbon::now();
      $model->save();
    }

    public function updateStatus(Request $request){
      $request = $request->all();
      $this->model = new Account();
      $this->updateDB($request);
      return $this->response();
    }

    public function update(Request $request){ 
      $request    = $request->all();
      $text = array('email' => $request['username']);
      $condition = null;
      $flag = false;
      if($this->customValidate($text) == true){
        $condition = array('email' => $request['username']);
        $flag = true;
      }
      else{
        $condition = array('username' => $request['username']);
      }
      $result = DB::table('accounts')->where($condition)->get();
      $parameter = null;
      if(sizeof($result) > 0){
        $parameter['id'] = $result[0]->id;
        $parameter['password'] = Hash::make($request['password']);
      }
      else{
        return array('message' => ['error' => [
          'status' => 100
        ]]);
      }  
      $request['password'] = $this->hashPassword($request['password']);
      $this->updateDB($parameter);
      return $this->response();
    }

    public function updatePassword(Request $request){ 
      $data = $request->all();
      $data['password'] = Hash::make($data['password']);
      $this->updateDB($data);
      return $this->response();
    }

    public function hashPassword($password){
      $data['password'] = Hash::make($password);
      return $data;
    }
    public function customValidate($text){
      $validation = array('email' => 'required|email'); 
      return $this->validateReply($text, $validation);
    }

    public function validateReply($text, $validation){
      $validator = Validator::make($text, $validation);
      if($validator->fails()){
        return false;
      }
      else
        return true;
    }

    public function retrieve(Request $request){
      $data = $request->all();
      $this->model = new Account();
      $result = $this->retrieveDB($data);

      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          // if paused on is not nnull then the pause_flag is fillable
          // otherwise null
          if($result[$i]['paused_on'] != null){
            $current = Carbon::now();
            $pausedOnDate = Carbon::createFromFormat('Y-m-d H:i:s', $result[$i]['paused_on']);
            $diff = $current->diffInSeconds($pausedOnDate, false);
            $this->response['data'][$i]['pause_flag'] = ($diff <= 0) ? false : true;
            $this->response['data'][$i]['paused_on_human'] = Carbon::createFromFormat('Y-m-d H:i:s',  $result[$i]['paused_on'])->copy()->tz('Asia/Manila')->format('D F j, Y');
          }else{
            $this->response['data'][$i]['pause_flag'] = false;
            $this->response['data'][$i]['paused_on_human'] = null;
          }
          
          $profileResult = AccountProfilePicture::where('account_id', '=', $result[$i]['id'])->orderBy('created_at','DESC')->get();
          $infoResult = AccountInformation::where('account_id', '=', $result[$i]['id'])->orderBy('created_at','DESC')->get();
          $this->response['data'][$i]['profile'] = (sizeof($profileResult) > 0) ? $profileResult[0] : null;
          $this->response['data'][$i]['information'] = (sizeof($infoResult) > 0) ? $infoResult : null;
          $this->response['data'][$i]['enabled_lesson'] = $this->checkEnabledLessons($result[$i]['id']);
          
          if($result[$i]['canceled_on']){
            $this->response['data'][$i]['canceled_on_human'] = Carbon::createFromFormat('Y-m-d H:i:s',  $result[$i]['canceled_on'])->copy()->tz('Asia/Manila')->format('D F j, Y');
           }else{
            $this->response['data'][$i]['canceled_on_human'] = null;
           }
         
        }
        return $this->response();
      }else{
        return $this->response();
      }
    }

    public function getCode(){
      $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 64);
      $result = Account::where('code', '=', $code)->get();
      if(sizeof($result) > 0){
        $this->getCode();
      }else{
        return $code;
      }
    }

    public function forgetPassword(Request $request){
      $request = $request->all();
      $accountResult = Account::where('email', '=', $request['email'])->orWhere('username', '=', $request['email'])->get();

      if(sizeof($accountResult) > 0){
        $host = array(
          'host'  => $request['host'],
          'api'   => $request['api'],
          'app'   => $request['app'],
          'email' => $request['host_email'],
          'app_title' => $request['app_title'],
          'web' => $request['web'],
          'browser' => $request['browser']
        );
        app('App\Http\Controllers\EmailController')->forgetPassword($accountResult[0]['id'], $host);
        return response()->json(array('data' => true));
      }else{
        return response()->json(array('data' => false));
      }
    }

    public function verify(Request $request){
      $data = $request->all();
      $result = Account::where('code', '=', $data['code'])->where('username', '=', $data['username'])->get();
      if(sizeof($result) > 0){
        $updateData = array(
          'id'  => $result[0]['id'],
          'status'  => 'VERIFIED'
        );
        $this->model = new Account();
        $this->updateDB($updateData);
        return $this->response();
      }else{
        // NOT FOUND
        return response()->json(array('data' => false));
      }
    }

    public function updateEmail(Request $request){
      $request = $request->all();
      $result = Account::where('email', '=', $request['email'])->get();
      $text = array('email' => $request['email']);
      if(sizeof($result) <= 0 && $this->customValidate($text) == true){
        $this->model = new Account();
        $updateData = array(
          'id' => $request['id'],
          'email' => $request['email']
        );
        $this->updateDB($updateData);
        if($this->response['data'] == true){
          $account = Account::where('id', '=', $request['id'])->get();
          // Get acount info for sending an email address
          // Sent Email via Job once the email is ready
          $host = array(
            'host'  => $request['host'],
            'api'   => $request['api'],
            'app'   => $request['app'],
            'email' => $request['host_email'],
            'host_email' => $request['host_email'],
            'app_title' => $request['app_title'],
            'web' => $request['web'],
            'browser' => $request['browser']
          );
          // dispatch(new EmailVerification($account[0], $host));
          return $this->response();
        }else{
          return response()->json(array('data' => false, 'error' => 'Unable to update please contact the support.'));
        }
      }else{
        return response()->json(array('data' => false, 'error' => 'Email already used.'));
      }
    }

    public function resendEmail(Request $request){
      $request = $request->all();
      $this->model = new Account();
      $retrieveData = array(
        'condition' => array(
          array(
            'value' => $request['condition'][0]['value'],
            'column' => $request['condition'][0]['column'],
            'clause' => $request['condition'][0]['clause']
          )
        )
      );
      $this->retrieveDB($retrieveData);
      if(sizeof($this->response['data']) > 0){
        // Dispatch verification
        $host = array(
          'host'  => $request['host'],
          'api'   => $request['api'],
          'app'   => $request['app'],
          'host_email' => $request['host_email'],
          'app_title' => $request['app_title'],
          'web' => $request['web'],
          'browser' => $request['browser']
        );
        return $this->response();
      }else{
        return $this->response();
      }
    }
    public function resetPassword(Request $request){
      $request = $request->all();
      $result = Account::where('username', '=', $request['username'])->where('code', '=', $request['code'])->get();
      if(sizeof($result) > 0){
        //reset 
        //send email for changes
        $updateData = array(
          'id' => $result[0]['id'],
          'password' => Hash::make($request['password'])
        );
        $host = array(
          'host'  => $request['host'],
          'api'   => $request['api'],
          'app'   => $request['app'],
          'host_email' => $request['host_email'],
          'app_title' => $request['app_title'],
          'web' => $request['web'],
          'browser' => $request['browser']
        );
        $this->model = new Account();
        $this->updateDB($updateData);
        if($this->response['data'] === true){
          app('App\Http\Controllers\EmailController')->changePassword($result[0]['id'], $host);
        }else{
          //
        }
        return $this->response();
      }else{
        return response()->json(array('data' => false));
      }
    }

    public function cancelPlans(Request $request){
      $data = $request->all();
      $feedback = new FeedBack();
      $feedback->account_id = $data['account_id'];
      $feedback->message = $data['message'];
      $feedback->save();
      return response()->json(
        array(
          'data'  => $feedback->id,
          'error' => null,
          'timestamps'  => Carbon::now()
        )
      );
    }
}