<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StripeSubscription;
use App\StripeWebhooks;
use App\Account;
use App\Coupon;
use App\Billing;
use App\CreditCard;
use App\AccountPaymentMethod;
use App\AccountInformation;
use App\FeedBack;
use Carbon\Carbon;
class StripeController extends TalkController
{
    protected $pk;
    protected $sk;
    protected $monthly = 49;
    protected $annually = 19;
    protected $pause = 9;
    public $stripe = null;

    public $paypalController = 'App\Http\Controllers\PayPalController';

    public function init($keys){
      $pk = ($keys['flag'] == false || $keys['flag'] == 'false') ? $keys['stripe']['dev_pk'] : $keys['stripe']['live_pk'];
      $sk = ($keys['flag'] == false  || $keys['flag'] == 'false') ? $keys['stripe']['dev_sk'] : $keys['stripe']['live_sk'];
      $this->stripe = new StripeWebhooks($pk, $sk);
    }

    public function cancelPlan(Request $request){
      $data = $request->all();
      $keys = $data['payment_keys'];
      $message = $data['message'];
      $accountId = $data['account_id'];
      $pk = ($keys['flag'] == false || $keys['flag'] == 'false') ? $keys['stripe']['dev_pk'] : $keys['stripe']['live_pk'];
      $sk = ($keys['flag'] == false  || $keys['flag'] == 'false') ? $keys['stripe']['dev_sk'] : $keys['stripe']['live_sk'];
      $stripe = new StripeWebhooks($pk, $sk);
      $subscription = StripeSubscription::where('account_id', '=', $accountId)->orderBy('created_at','desc')->first();
      if($subscription){


        $endDate = date('Y-m-d H:i:s', $stripe->retrieveSubscriptionCurrentEndDate($subscription->subscription));
        $response = $stripe->cancelSubscription($subscription->subscription);
        if($response){

          $feedBack = new FeedBack();
          $feedBack->account_id = $accountId;
          $feedBack->message = $message;
          $feedBack->created_at = Carbon::now();
          $feedBack->save();

          $date1 = Carbon::now()->startOfDay();
          $date2 = Carbon::createFromFormat('Y-m-d H:i:s', $endDate)->startOfDay();
          $diff = $date1->diffInDays($date2);

          if($diff > 0){
            Account::where('id', '=', $accountId)->update(
              array(
              'canceled_on'     => $endDate,
              'paused_on'       => null,
              'updated_at'      => Carbon::now()
            ));
          }else{
            Account::where('id', '=', $accountId)->update(
              array(
                'payment_status'  => 'failed',
                'canceled_on'     => $endDate,
                'paused_on'       => null,
                'updated_at'      => Carbon::now()
            ));
          }

          $this->updateBilling($accountId);
          StripeSubscription::where('account_id', '=', $accountId)->update(
            array(
              'deleted_at'  => Carbon::now()
            )
          );

          return response()->json(
            array(
              'data'  => true,
              'error' => null,
              'timestamp' => Carbon::now()
            )
          );
        }else{
          return response()->json(
            array(
              'data'  => false,
              'error' => 'Unable to Cancel your subsctiption. Please contact the administrator.',
              'timestamp' => Carbon::now()
            )
          );
        }
      }else{
        return response()->json(
          array(
            'data'  => false,
            'error' => 'Invalid Subscription. Unable to Cancel your subsctiption. Please contact the administrator.',
            'timestamp' => Carbon::now()
          )
        );
      }
    }

    public function newSubscription(Request $request){
      $data = $request->all();
      // create subbscription
      $keys = $data['payment_keys'];
      $products = $data['products'];
      $coupon = $data['coupon'];
      $pk = ($keys['flag'] == false || $keys['flag'] == 'false') ? $keys['stripe']['dev_pk'] : $keys['stripe']['live_pk'];
      $sk = ($keys['flag'] == false  || $keys['flag'] == 'false') ? $keys['stripe']['dev_sk'] : $keys['stripe']['live_sk'];
      $stripe = new StripeWebhooks($pk, $sk);
      $subscription = null;
      
      $creditCard = CreditCard::where('account_id', '=', $data['account_id'])->first();

      $account = Account::where('id', '=', $data['account_id'])->first();
      $title = null;
      if($data['plan'] == 'monthly'){
        $title = " Talkfluent Monthly Subscription for US$".$this->monthly;
      }else if($data['plan'] == 'annually'){
        $title = " Talkfluent Annual Subscription for US$".($this->annually * 12);
      }else if($data['plan'] == 'pause'){
        $title = " Talkfluent Pause Account Monthly Subscription for US$".$this->pause;
      }else{
        //
      }

      $discount = 0;
      $totalAmount = 0;
      $monthly = $this->monthly;
      $yearly = ($this->annually) * 12;
      $pause = $this->pause;
      $newPlan = $data['plan'];
      $couponId = null;
      $stripeCouponId = $coupon;
      if($coupon !== null){
        $coupon = Coupon::where('code', '=', $coupon)->first();
        if($newPlan == 'monthly'){
          $discount = ($coupon->type == 'percentage') ? $monthly * (floatval($coupon->value) / 100) : floatval($coupon->value);
          $totalAmount = $monthly - $discount;
        }else if($newPlan == 'annually'){
          $discount = ($coupon->type == 'percentage') ? $yearly * (floatval($coupon->value) / 100) : floatval($coupon->value);
          $totalAmount = $yearly - $discount;
        }else if($newPlan == 'pause'){
          $discount = ($coupon->type == 'percentage') ? $pause * (floatval($coupon->value) / 100) : floatval($coupon->value);
          $totalAmount = $pause - $discount;
        }else{
          //
        }
        $couponId = ($coupon) ? $coupon->id: null;
      }else{
        $discount = 0;
        $couponId = null;
        if($newPlan == 'monthly'){
          $totalAmount = $monthly;
        }else if($newPlan == 'annually'){
          $totalAmount = $yearly;
        }else if($newPlan == 'pause'){
          $totalAmount = $pause;
        }
      }

      $charge = $stripe->chargeCustomer($account->email, $creditCard->source, $creditCard->customer, $totalAmount * 100, $title);

      $trialPeriod = 0;

      if($data['plan'] == 'monthly' || $data['plan'] == 'pause'){
        $trialPeriod = Carbon::now()->copy()->diffInDays(Carbon::now()->copy()->addMonth());
      }else{
        $trialPeriod = Carbon::now()->copy()->diffInDays(Carbon::now()->copy()->addYear());
      }

      if($charge && $charge->status != "failed"){
        if($stripeCouponId !== null){
          $subscription = $stripe->createSubscriptionWithCoupon($data['customer_id'], $this->getPlan($data['plan']), $trialPeriod, $stripeCouponId);
        }else{
          $subscription = $stripe->createSubscription($data['customer_id'], $this->getPlan($data['plan']), $trialPeriod);
        }
      }else{
        return response()->json(
          array(
            'data' => null,
            'error' => 'Failed to charge your card.',
            'timestamp' => Carbon::now()
          )
        );
      }
      
      // check if coupon exist
      // check if new coupon as parameter
      // check

      if($subscription->id){
        $_stripe_subscription = new StripeSubscription();
        $_stripe_subscription->account_id = $data['account_id'];
        $_stripe_subscription->credit_card_id = $data['credit_card_id'];
        $_stripe_subscription->subscription = $subscription->id;
        $_stripe_subscription->created_at = Carbon::now();
        $_stripe_subscription->save();

        $startDate = date('Y-m-d H:i:s', $stripe->retrieveSubscriptionCurrentStartDate($subscription->id));
        $endDate = date('Y-m-d H:i:s', $stripe->retrieveSubscriptionCurrentEndDate($subscription->id));

        if($newPlan == 'pause'){
          Account::where('id', '=', $data['account_id'])->update(
            array(
              'payment_status' => 'active',
              'plan' => $data['plan'],
              'paused_on' => $startDate,
              'canceled_on' => null
            ));
        }else{
          Account::where('id', '=', $data['account_id'])->update(
            array(
              'payment_status' => 'active',
              'plan' => $data['plan'],
              'paused_on' => null,
              'canceled_on' => null
          ));
        }
        
        $descriptionAmount = null;
        if($data['plan'] == 'monthly' || $data['plan'] == 'pause'){
          $descriptionAmount = 'US$'.($subscription->plan->amount / 100).' per month';
        }else if($data['plan'] == 'annually'){
          $descriptionAmount = 'US$'.(($subscription->plan->amount / 100) * 12).' per year';
        }else{
          //
        }

        $this->updateBilling($data['account_id']);

        $_billing = new Billing();
        $_billing->account_id = $data['account_id'];
        $_billing->status = $charge->status;
        $_billing->coupon_id = $couponId;
        $_billing->start_date = Carbon::createFromFormat('Y-m-d H:i:s', $startDate);
        $_billing->end_date = Carbon::createFromFormat('Y-m-d H:i:s', $endDate);
        $_billing->payment_method = 'credit_card';
        $_billing->currency = 'usd';
        $_billing->description = 'Renew Plan';
        $_billing->description_amount = $descriptionAmount;
        $_billing->total_amount = $totalAmount * 100;
        $_billing->taxes_and_fees = 0;
        $_billing->discount_total_amount = $discount;
        $_billing->created_at = Carbon::now();
        $_billing->save();

        // send email here

        $billingDetails = array(
          'description' => $descriptionAmount,
          'unit_price'  => $totalAmount,
          'qty'         => 1,
          'amount'      => $totalAmount,
          'subtotal'    => $totalAmount,
          'tax'         => 0,
          'discount'    => $discount,
          'total'       => $totalAmount
        );

        $returnUrl = app('App\Http\Controllers\EmailController')->receipt($data['account_id'], null, $billingDetails);

        return response()->json(array('data' => $_stripe_subscription->id));
      }else{
        return response()->json(
          array(
            'data' => null,
            'error' => 'Unable to create subsctiption',
            'timestamps' => Carbon::now()
          )
        );
      }
    }

    public function getPrice($plan, $productPlans){
      switch ($plan) {
        case 'monthly':
          return $productPlans['monthly']['price'];
        case 'annually':
          return $productPlans['annually']['price'];
        case 'semi_annual':
          return $productPlans['semi_annual']['price'];
        case 'pause':
          return $productPlans['pause']['price'];
      }
    }

    public function addNewPaymentMethod(Request $request){
      $data = $request->all();
      $this->init($data['payment_keys']);

      // check and remove previous agreement
      // create new agreement
      // save information of the card
      // check if the date is current then add to billing history, otherwise just ignore

      if($this->stripe == null){
        return false;
      }

      $accountId = $data['account_id'];
      $stripeCard = StripeSubscription::where('account_id', '=', $accountId)->orderBy('created_at', 'desc')->first();
      
      $endDate = null;
      if($stripeCard){
        $endDate = date('Y-m-d H:i:s', $this->stripe->retrieveSubscriptionCurrentEndDate($stripeCard->subscription));
        StripeSubscription::where('id', '=', $stripeCard->id)->update(array(
          'deleted_at' => Carbon::now()
        ));
        $this->stripe->cancelSubscription($stripeCard->subscription);
      }

      app($this->paypalController)->cancelAgreementByAccountId($accountId, $data['paypal']);

      $email = $data['email'];
      $source = $data['source'];
      $accountId = $data['account_id'];
      $accountInformation = AccountInformation::where('account_id', '=', $accountId)->first();
      $name = (sizeof(array($accountInformation)) > 0) ? $accountInformation->first_name .' '. $accountInformation->last_name : null;
      $customer = $this->stripe->createCustomer($email, $source['id'], $name);
      
      if($customer->id){
        // save credit card
        CreditCard::where('account_id', '=', $accountId)->update(array('status' => 'inactive'));
        AccountPaymentMethod::where('account_id', '=', $accountId)->update(array('status' => 'inactive'));
        $_creditcards = new CreditCard();
        $_creditcards->account_id = $accountId;
        $_creditcards->brand = $source['card']['brand'];
        $_creditcards->exp_year = $source['card']['exp_year'];
        $_creditcards->exp_month = $source['card']['exp_month'];
        $_creditcards->last4 = $source['card']['last4'];
        $_creditcards->source = $source['id'];
        $_creditcards->customer = $customer->id;
        $_creditcards->status = 'active';
        $_creditcards->created_at = Carbon::now();
        $_creditcards->save();

        $accountPaymentMethod = new AccountPaymentMethod();
        $accountPaymentMethod->account_id = $accountId;
        $accountPaymentMethod->source = $_creditcards->id;
        $accountPaymentMethod->method = 'stripe';
        $accountPaymentMethod->status = 'active';
        $accountPaymentMethod->created_at = Carbon::now();
        $accountPaymentMethod->save();
        
        $currentDate = Carbon::now();
        $trialPeriod = 0;
        $coupon = null;
        $subscriptionResponse = $this->stripe->createSubscription($customer->id, $this->getPlan($data['plan']), $trialPeriod);

        
        if($subscriptionResponse->id){
          $_account_stripe_card = new StripeSubscription();
          $_account_stripe_card->account_id = $data['account_id'];
          $_account_stripe_card->credit_card_id = $_creditcards->id;
          $_account_stripe_card->subscription = $subscriptionResponse->id;
          $_account_stripe_card->save();


          if($endDate){
            $diff = Carbon::now()->diffInSeconds($endDate);
            if($diff < 0){
              $discount = 0;
              $totalAmount = 0;
              $monthly = $this->monthly;
              $yearly = ($this->annually) * 12;
              $pause = $this->pause;
              $couponId = null;

              $discount = 0;
              $couponId = null;
              if($data['plan'] == 'monthly'){
                $totalAmount = $monthly;
              }else if($data['plan'] == 'annually'){
                $totalAmount = $yearly;
              }else if($data['plan'] == 'pause'){
                $totalAmount = $pause;
              }

              $descriptionAmount = null;
              $description = null;
              $startDate = Carbon::now()->addDay($trialPeriod);

              if($data['plan'] == 'monthly'){
                $descriptionAmount = 'US$'.($subscriptionResponse->plan->amount / 100).' per month';
                $description = 'Changed payment method';
                $endDate = Carbon::now()->addMonth();
                // Update accounts table while set the paused_on to null

              }else if($data['plan'] == 'annually'){
                $descriptionAmount = 'US$'.(($subscriptionResponse->plan->amount / 100) * 12).' per year';
                $description = 'Changed payment method';
                $endDate = Carbon::now()->addYear();
                // Update accounts table while set the paused_on to null
                
              }


              // save billing
              $_billing = new Billing();
              $_billing->account_id = $data['account_id'];
              $_billing->coupon_id = $couponId;
              $_billing->status = 'created';
              $_billing->start_date = $startDate;
              $_billing->end_date = $endDate;
              $_billing->payment_method = 'credit_card';
              $_billing->currency = 'usd';
              $_billing->description = $description;
              $_billing->description_amount = $descriptionAmount;
              $_billing->total_amount = $totalAmount * 100;
              $_billing->taxes_and_fees = 0;
              $_billing->discount_total_amount = $discount;
              $_billing->created_at = Carbon::now();
              $_billing->save();
            }
          }
          
          $this->response['data'] = true;
          return $this->response();
        }else{
          $this->response['data'] = null;
          $this->response['error'] = "Unable to create subsctiption";
          return $this->response();
        }
      }else{
        $this->response['data'] = null;
        $this->response['error'] = "Unable to create customer id";
        return $this->response();
      } 
    }

    public function cancelPreviousStripeSubscription($accountId){
      if($this->stripe == null){
        return false;
      }
      $stripeCard = StripeSubscription::where('account_id', '=', $accountId)->orderBy('created_at', 'desc')->first();
      if($stripeCard){
        return $this->stripe->cancelSubscription($stripeCard->subscriptionId);
      }else{
        return null;
      } 
    }

    public function upgrade(Request $request){
      $data = $request->all();
      $keys = $data['payment_keys'];
      // get the current subscription
      // get the plan
      // update with prorate, note the prorate will take effect on next billing
      $pk = ($keys['flag'] == false || $keys['flag'] == 'false') ? $keys['stripe']['dev_pk'] : $keys['stripe']['live_pk'];
      $sk = ($keys['flag'] == false  || $keys['flag'] == 'false') ? $keys['stripe']['dev_sk'] : $keys['stripe']['live_sk'];
      $stripeCard = StripeSubscription::where('account_id', '=', $data['account_id'])->orderBy('created_at', 'desc')->first();
      $account = Account::where('id', '=', $data['account_id'])->first();
      $newPlan = $data['plan'];
      if($account && $account->paused_on != null && $newPlan == 'pause'){
        $current = Carbon::now();
        $pausedOnDate = Carbon::createFromFormat('Y-m-d H:i:s', $account->paused_on);
        $diff = $current->diffInSeconds($pausedOnDate, false);

        if($diff > 0){
          return response()->json(array(
            'data'  => null,
            'error' => 'Unable to upgrade due to your account paused account plan is not yet effective.',
            'timestamps'  => Carbon::now()
          ));
        }
      }

      if($stripeCard) {
        if(($newPlan !== 'pause') || ($newPlan == 'pause' && $this->checkEnabledLessons($data['account_id']) == true)){
          //execute when the new plan is pause and enabled lesson is true
          //$customerId, $subscriptionId, $newPlanId
          $stripe = new StripeWebhooks($pk, $sk);
          $creditCards = CreditCard::where('id', '=', $data['credit_card_id'])->first();
          $subscriptionId = $stripeCard->subscription;
          $customerId = $creditCards->customer;
          $startDate = date('Y-m-d H:i:s', $stripe->retrieveSubscriptionCurrentStartDate($subscriptionId));
          $endDate = date('Y-m-d H:i:s', $stripe->retrieveSubscriptionCurrentEndDate($subscriptionId));

          $newPlanId = $this->getPlan($newPlan);
          $cancelResponse = $stripe->cancelSubscription($subscriptionId);
          
          if($cancelResponse){
            // create new subscription
            StripeSubscription::where('account_id', '=', $data['account_id'])->where('credit_card_id', '=', $data['credit_card_id'])->update(array('deleted_at' => Carbon::now()));
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDate);
            $currentDate = Carbon::now();
            $trialPeriod = $currentDate->diffInDays($endDate);
            $trialPeriod = ($trialPeriod > 0) ? $trialPeriod + 1 : 0;
            $coupon = $data['coupon'];
            $subscriptionResponse = null;
            if($coupon != null){
              $subscriptionResponse = $stripe->createSubscriptionWithCoupon($customerId, $newPlanId, $trialPeriod, $coupon);
            }else{
              $subscriptionResponse = $stripe->createSubscription($customerId, $newPlanId, $trialPeriod);
            }
            
            if($subscriptionResponse->id){
              $_account_stripe_card = new StripeSubscription();
              $_account_stripe_card->account_id = $data['account_id'];
              $_account_stripe_card->credit_card_id = $data['credit_card_id'];
              $_account_stripe_card->subscription = $subscriptionResponse->id;
              $_account_stripe_card->save();

              $discount = 0;
              $totalAmount = 0;
              $monthly = $this->monthly;
              $yearly = ($this->annually) * 12;
              $pause = $this->pause;
              $couponId = null;
              if($coupon !== null){
                $coupon = Coupon::where('code', '=', $coupon)->first();
                if($newPlan == 'monthly'){
                  $discount = ($coupon->type == 'percentage') ? $monthly * (floatval($coupon->value) / 100) : floatval($coupon->value);
                  $totalAmount = $monthly - $discount;
                }else if($newPlan == 'annually'){
                  $discount = ($coupon->type == 'percentage') ? $yearly * (floatval($coupon->value) / 100) : floatval($coupon->value);
                  $totalAmount = $yearly - $discount;
                }else if($newPlan == 'pause'){
                  $discount = ($coupon->type == 'percentage') ? $pause * (floatval($coupon->value) / 100) : floatval($coupon->value);
                  $totalAmount = $pause - $discount;
                }else{
                  //
                }
                $couponId = ($coupon) ? $coupon->id: null;
              }else{
                $discount = 0;
                $couponId = null;
                if($newPlan == 'monthly'){
                  $totalAmount = $monthly;
                }else if($newPlan == 'annually'){
                  $totalAmount = $yearly;
                }else if($newPlan == 'pause'){
                  $totalAmount = $pause;
                }
              }

              $descriptionAmount = null;
              $description = null;
              $startDate = Carbon::now()->addDay($trialPeriod);
              $endDate = null;
              if($newPlan == 'monthly'){
                $descriptionAmount = 'US$'.($subscriptionResponse->plan->amount / 100).' per month';
                $description = 'Upgrade Monthly';
                $endDate = Carbon::now()->addDay($trialPeriod)->addMonth();
                // Update accounts table while set the paused_on to null
                Account::where('id', '=', $data['account_id'])->update(array(
                  'plan' => $newPlan,
                  'updated_at'  => Carbon::now(),
                  'paused_on' => null
                ));
              }else if($newPlan == 'annually'){
                $descriptionAmount = 'US$'.(($subscriptionResponse->plan->amount / 100) * 12).' per year';
                $description = 'Upgrade Annually';
                $endDate = Carbon::now()->addDay($trialPeriod)->addYear();
                // Update accounts table while set the paused_on to null
                Account::where('id', '=', $data['account_id'])->update(array(
                  'plan' => $newPlan,
                  'updated_at'  => Carbon::now(),
                  'paused_on' => null
                ));
              }else if($newPlan == 'pause'){
                $descriptionAmount = 'US$'.($subscriptionResponse->plan->amount / 100).' per month';
                $description = 'Upgrade Pause Account';
                $endDate = Carbon::now()->addDay($trialPeriod)->addMonth();

                // Update accounts table and set the paused_on to effective date while do not change the plan
                // let the job change the plan when the time for paused_on date is the same with current date or less
                Account::where('id', '=', $data['account_id'])->update(array(
                  'updated_at'  => Carbon::now(),
                  'paused_on' => $startDate
                ));
              }else{
                //
              }

              $this->updateBilling($data['account_id']);

              // save billing
              $_billing = new Billing();
              $_billing->account_id = $data['account_id'];
              $_billing->coupon_id = $couponId;
              $_billing->status = 'created';
              $_billing->start_date = $startDate;
              $_billing->end_date = $endDate;
              $_billing->payment_method = 'credit_card';
              $_billing->currency = 'usd';
              $_billing->description = $description;
              $_billing->description_amount = $descriptionAmount;
              $_billing->total_amount = $totalAmount * 100;
              $_billing->taxes_and_fees = 0;
              $_billing->discount_total_amount = $discount;
              $_billing->created_at = Carbon::now();
              $_billing->save();

              // send email here
            }
          }
          return response()->json(array('data' => $cancelResponse, 'end_date' => $endDate, 'start_date' => $startDate, 'trial_period' => $trialPeriod));
        }
      }else if($newPlan == 'pause' && $this->checkEnabledLessons($data['account_id']) == false){
        return response()->json(array(
          'data'  => null,
          'error' => 'Unable to upgrade to pause account since you have no lessons or topics visited.',
          'timestamps'  => Carbon::now()
        ));
      }else{
        // check if exist from paypal
        $paypalAgrement = app($this->paypalController)->cancelAgreementByAccountId($data['account_id'], $data['config']);
        if($paypalAgrement){
          // create new agreement here
          // $this->newSubscription($request);
          echo json_encode($paypalAgrement);
        }else{
          return response()->json(array(
            'data' => null,
            'error' => "Unable to upgrade since you don't payment method.",
            'timestamps'  => Carbon::now()
          ));
        }
      }
    }

    public function getPlan($newPlan){
      switch ($newPlan) {
        case 'monthly':
          return env('STRIPE_MONTHLY');
        case 'annually':
          return env('STRIPE_ANNUALLY');
        case 'pause':
          return env('STRIPE_PAUSE');
      }
    }

}
