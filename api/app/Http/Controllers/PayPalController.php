<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\ShippingAddress;
use PayPal\Api\AgreementStateDescriptor;
use App\PayPalPlan;
use App\PayPalAgreement;
use App\AccountPaymentMethod;
use App\Billing;
use Carbon\Carbon;
use App\AccountPayPalCreation;
use App\Account;
use App\PayPalAccount;
use App\FeedBack;
class PayPalController extends TalkController
{
    private $apiContext;
    private $mode;
    private $client_id;
    private $secret;
    private $trialPeriod = 7;

    private $monthly = 49;
    private $annually = 19;
    private $pause = 9;
    private $returnUrl;
    private $cancelUrl;
    private $approvalUrl;

    private $remainingDays;

    // Create a new instance with our paypal credentials
    public function init($config) {
        // Detect if we are running in live mode or sandbox
        if($config['mode'] == 'live'){
          $this->client_id = env('PAYPAL_LIVE_CLIENT_ID');
          $this->secret = env('PAYPAL_LIVE_SECRET');
        } else {
          $this->client_id = env('PAYPAL_SANDBOX_CLIENT_ID');
          $this->secret = env('PAYPAL_SANDBOX_SECRET');
        }

        // Set the Paypal API Context/Credentials
        $this->apiContext = new ApiContext(new OAuthTokenCredential($this->client_id, $this->secret));

        $this->returnUrl = $config['return_url'];
        $this->cancelUrl = $config['cancel_url'];
    }

    public function createOnBilling(Request $request){
      $data = $request->all();
      $this->trialPeriod = 0;
      $returnUrl = $this->handlerWithoutSetupFee($data['account_id'], $data['plan'], $data['nickname'], $data['config']);
      return response()->json(
        array(
          'return_url' => $returnUrl,
          'timestamp' => Carbon::now()
        )
      );
    }

    public function upgrade(Request $request){
      // get the enabled lesson if pause is okay
      $data = $request->all();
      $config = $data['config'];
      $paymentStatus = $data['payment_status'];
      $accountId = $data['account_id'];
      $enabledLesson = $this->checkEnabledLessons($accountId);
      $account = Account::where('id', '=', $accountId)->first();
      $newPlan = $data['plan'];
      if($enabledLesson == false && $newPlan == 'pause'){
        return response()->json(array(
          'data'  => null,
          'error' => 'Unable to upgrade to pause account since you have no lessons or topics visited.',
          'timestamps'  => Carbon::now()
        ));
      }
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
      
      $this->init($config['paypal']);
      // retrieve agreement id using the paypal agreement model
      // get the remaining days for effectivity using the agreement details
      // cancel first the previous agreement
      // create new agreement
      // send redirect
      // send execute again

      $data = $request->all();
      $pAgreement = PayPalAgreement::where('account_id', '=', $data['account_id'])->first();
      
      $plan = $account->plan;

      $amount = 0;
      if($plan == 'monthly'){
        $amount = $this->monthly;
      }else if($plan == 'annually'){
        $amount = $this->annually * 12;
      }else if($plan == 'pause'){
        $amount = $this->pause;
      }else{
        //
      }
      // get the remaining days
      // if days is less than a month then create an agreement
      // else return not allowed for now
        
      $agreement = new Agreement();
      $agreement->setId($pAgreement->agreement);
      $stateDescriptor = new AgreementStateDescriptor();
      $stateDescriptor->setNote('Upgrade Plan');
      $stateDescriptor->setAmount(new Currency(array('value' => ($amount), 'currency' => 'USD')));

      try {

        $agreementDetails = $agreement->get($pAgreement->agreement, $this->apiContext);
        if($agreementDetails->id && $agreementDetails->state == 'Active'){
          $this->trialPeriod = $this->getRemainingDays($agreementDetails->agreement_details->next_billing_date);
          $response = $agreement->cancel($stateDescriptor, $this->apiContext);
          if($response == true){
            PayPalAgreement::where('account_id', '=', $data['account_id'])->update(array(
              'state'  => 'cancelled',
              'deleted_at' => Carbon::now()
            ));
            // if($newPlan == 'pause'){
            //   Account::where('id', '=', $data['account_id'])->update(array(
            //     'plan'  => $newPlan
            //   ));
            // }
            if($newPlan == 'monthly'){
              $this->redirectMonthlyWithoutSetupFee($data['account_id'], $pAgreement->name, $config, 'upgrade');
            }else if($newPlan == 'annually'){
              $this->redirectAnnuallyWithoutSetupFee($data['account_id'], $pAgreement->name, $config, 'upgrade');
            }else if($newPlan == 'pause'){
              $this->redirectPause($data['account_id'], $pAgreement->name, $config, 'upgrade');
            }else{
              //
            }
            return response()->json(array(
              'data' => $this->approvalUrl
            ));
          }else{
            return response()->json(array(
              'data'  => null,
              'error' => "Unable to upgrade since you don't payment method.",
              'timestamps'  => Carbon::now()
            ));
          }
        }else if($agreementDetails->id && $agreementDetails->state == 'Pending'){
          return response()->json(array(
            'data'  => null,
            'error' => "Unable to upgrade due to your PayPal's Agreement still at pending status, please back to us tomorrow!",
            'timestamps'  => Carbon::now()
          ));
        }else{
          return response()->json(array(
            'data' => $this->approvalUrl
          ));
        }
      }catch(\Exception\PayPalConnectionException $ex) {
        echo $ex->getCode();
        echo $ex->getData();
        die($ex);
      }catch(\Exception $ex) {
        echo $ex->getCode();
        // echo $ex->getData();
        die($ex);
      }
    }

    public function getRemainingDays($date){
      $endDate = null;
      $extracted = null;
      if(strpos($date, 'T')){
        $extracted = explode('T', $date);
        $endDate = $extracted[0];
      }else if(strpos($date, 't')){
        $extracted = explode('t', $date);
        $endDate = $extracted[0];
      }
      if(strpos($extracted[1], 'Z')){
        $newExtract = explode('Z', $extracted[1]);
        $endDate = $endDate . ' '.$newExtract[0];
      }else if(strpos($extracted[1], 'z')){
        $newExtract = explode('z', $extracted[1]);
        $endDate = $endDate . ' '.$newExtract[0];
      }
      $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDate)->startOfDay();
      $cDate = Carbon::now()->startOfDay();
      return $cDate->diffInDays($endDate);
    }

    public function getDateTime($date){
      $endDate = null;
      $extracted =null;
      if(strpos($date, 'T')){
        $extracted = explode('T', $date);
        $endDate = $extracted[0];
      }else if(strpos($date, 't')){
        $extracted = explode('t', $date);
        $endDate = $extracted[0];
      }
      if(strpos($extracted[1], 'Z')){
        $newExtract = explode('Z', $extracted[1]);
        $endDate = $endDate . ' '.$newExtract[0];
      }else if(strpos($extracted[1], 'z')){
        $newExtract = explode('z', $extracted[1]);
        $endDate = $endDate . ' '.$newExtract[0];
      }
      return $endDate;
    }

    public function reActivate(Request $request){
      $data = $request->all();
      $this->init($data['paypal']);
      
      $pAgreement = PayPalAgreement::where('account_id', '=', $data['account_id'])->
      where('state', '=', 'cancelled')->orderBy('created_at', 'DESC')->first();

      $agreement = new Agreement();
      $agreement->setId($pAgreement->agreement);

      $stateDescriptor = new AgreementStateDescriptor();
      $stateDescriptor->setNote('Reactivate Plan');
      try {
        $response = $agreement->reActivate($stateDescriptor, $this->apiContext);

        PayPalAgreement::where('id', '=', $pAgreement->id)->update(array(
          'state' => 'active'
        ));
        
      } catch (\Exception $ex) {
        echo json_encode($ex->getData());
        echo json_encode($ex->getCode());
        die($ex);
      } catch(\Exception\PayPalConnectionException $ex){
        echo json_encode($ex->getData());
        echo json_encode($ex->getCode());
        die($ex);
      }
    }

    public function handler($accountId, $plan, $name, $config){
      if($plan == 'monthly'){
        $this->redirectMonthly($accountId, $name, $config);
      }else if($plan == 'annually'){
        $this->redirectAnnually($accountId, $name, $config);
      }else if($plan == 'pause'){
        // $this->redirectPause($accountId, $name, $config);
      }
      return $this->approvalUrl;
    }

    public function handlerWithoutSetupFee($accountId, $plan, $name, $config){
      if($plan == 'monthly'){
        $this->redirectMonthlyWithoutSetupFee($accountId, $name, $config, 'recreate');
      }else if($plan == 'annually'){
        $this->redirectAnnuallyWithoutSetupFee($accountId, $name, $config, 'recreate');
      }else if($plan == 'pause'){
        $this->redirectPause($accountId, $name, $config, 'recreate');
      }
      return $this->approvalUrl;
    }

    public function createPlanMonthlyWithoutSetupFee($setupAmount, $method){
      $nameFlag = ($method == 'upgrade') ? '' : '(Renewal)';
      $name = 'Talkfluent Monthly Subscription without Setup Fee'.$nameFlag;
      $description = 'Talkfluent Plan';

      $result = PayPalPlan::where('name', '=', $name)->get();

      if(sizeof($result) <= 0){
        $plan = new Plan();
        $plan->setName($name)
        ->setDescription($description)
        ->setType('fixed');

        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Regular Payments')
          ->setType('REGULAR')
          ->setFrequency('MONTH')
          ->setFrequencyInterval("1")
          ->setCycles("1")
          ->setAmount(new Currency(array('value' => $this->monthly, 'currency' => 'USD')));

        $chargeModel = new ChargeModel();
        $chargeModel->setType('SHIPPING')
        ->setAmount(new Currency(array('value' => 0, 'currency' => 'USD')));

        $paymentDefinition->setChargeModels(array($chargeModel));

        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl($this->returnUrl)
        ->setCancelUrl($this->cancelUrl)
        ->setAutoBillAmount("yes")
        ->setInitialFailAmountAction("CONTINUE")
        ->setMaxFailAttempts("0")
        ->setSetupFee(new Currency(array('value' => $setupAmount, 'currency' => 'USD')));

        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);

        try {
            $createdPlan = $plan->create($this->apiContext);
            try {
              $patch = new Patch();
              $value = new PayPalModel('{"state":"ACTIVE"}');
              $patch->setOp('replace')
                ->setPath('/')
                ->setValue($value);
              $patchRequest = new PatchRequest();
              $patchRequest->addPatch($patch);
              $createdPlan->update($patchRequest, $this->apiContext);
              $plan = Plan::get($createdPlan->getId(), $this->apiContext);
              $model = new PayPalPlan();
              $insertData = array(
                'plan'        => $plan->getId(),
                'name'        => $name,
                'description' => $description,
                'state'       => $plan->getState(),
                'frequency'   => 'Month',
                'amount'      => ($this->monthly),
                'currency'    => 'USD',
                'created_at'  => Carbon::now()
              );
              $model->insert($insertData);
              return $plan->getId();
            }catch(\Exception\PayPalConnectionException $ex) {
              echo $ex->getCode();
              echo $ex->getData();
              die($ex);
            }catch(\Exception $ex) {
              echo json_encode($ex->getData());
              echo json_encode($ex->getCode());
              die($ex);
            }
          } catch (\Exception $ex) {
            echo json_encode($ex->getData());
            echo json_encode($ex->getCode());
            die($ex);
          } catch(\Exception\PayPalConnectionException $ex){
            echo json_encode($ex->getData());
            echo json_encode($ex->getCode());
            die($ex);
          }
      }else{
        return $result[0]['plan'];
      }
    }

    public function createPlanAnnuallyWithoutSetupFee($setupAmount, $method){
      // create plan without setup fee
      // make the effective dave after the remaining days
      $nameFlag = ($method == 'upgrade') ? '' : '(Renewal)';
      $name = 'Talkfluent Annual Subscription without Setup Fee'.$nameFlag;
      $description = 'Talkfluent Plan';

      $result = PayPalPlan::where('name', '=', $name)->get();
      if(sizeof($result) <= 0){
        $plan = new Plan();
        $plan->setName($name)
          ->setDescription($description)
          ->setType('fixed');

          $paymentDefinition = new PaymentDefinition();
          $paymentDefinition->setName('Regular Payment')
          ->setType('REGULAR')
          ->setFrequency('YEAR')
          ->setFrequencyInterval('1')
          ->setCycles('1')
          ->setAmount(new Currency(array('value' => ($this->annually * 12), 'currency' => 'USD')));

          $chargeModel = new ChargeModel();
          $chargeModel->setType('SHIPPING')
          ->setAmount(new Currency(array('value' => 0, 'currency' => 'USD')));

          $paymentDefinition->setChargeModels(array($chargeModel));

          $merchantPreferences = new MerchantPreferences();
          $merchantPreferences->setReturnUrl($this->returnUrl)
          ->setCancelUrl($this->cancelUrl)
          ->setAutoBillAmount("yes")
          ->setInitialFailAmountAction("CONTINUE")
          ->setMaxFailAttempts("0")
          ->setSetupFee(new Currency(array('value' => $setupAmount, 'currency' => 'USD')));

          $plan->setPaymentDefinitions(array($paymentDefinition));
          $plan->setMerchantPreferences($merchantPreferences);

          try {
            $createdPlan = $plan->create($this->apiContext);
            try {
              $patch = new Patch();
              $value = new PayPalModel('{"state":"ACTIVE"}');
              $patch->setOp('replace')
                ->setPath('/')
                ->setValue($value);
              $patchRequest = new PatchRequest();
              $patchRequest->addPatch($patch);
              $createdPlan->update($patchRequest, $this->apiContext);
              $plan = Plan::get($createdPlan->getId(), $this->apiContext);
              $model = new PayPalPlan();
              $insertData = array(
                'plan'        => $plan->getId(),
                'name'        => $name,
                'description' => $description,
                'state'       => $plan->getState(),
                'frequency'   => 'Year',
                'amount'      => ($this->annually * 12),
                'currency'    => 'USD',
                'created_at'  => Carbon::now()
              );
              $model->insert($insertData);
              return $plan->getId();
            }catch(\Exception\PayPalConnectionException $ex) {
              echo $ex->getCode();
              echo $ex->getData();
              die($ex);
            }catch(\Exception $ex) {            
              echo json_encode($ex->getData());
              echo json_encode($ex->getCode());
              die($ex);
            }
          } catch (\Exception $ex) {
            echo json_encode($ex->getData());
            echo json_encode($ex->getCode());
            die($ex);
          } catch(\Exception\PayPalConnectionException $ex){
            echo json_encode($ex->getData());
            echo json_encode($ex->getCode());
            die($ex);
          }
      }else{
        return $result[0]['plan'];
      }
    }

    public function createPlanAnnually(){
      // Instantiate Plan
      $name = 'Talkfluent Annual Subscription';
      $description = 'Talkfluent Plan';
      $result = PayPalPlan::where('name', '=', $name)->get();
      if(sizeof($result) <= 0){
          $plan = new Plan();
          $plan->setName($name)
            ->setDescription($description)
            ->setType('fixed');

          // Instantiate Payment Definition
          $paymentDefinition = new PaymentDefinition(); 
          $paymentDefinition->setName('Regular Payments')
            ->setType('REGULAR')
            ->setFrequency('YEAR')
            ->setFrequencyInterval("1")
            ->setCycles("1")
            ->setAmount(new Currency(array('value' => ($this->annually * 12), 'currency' => 'USD')));

          // Add Charge Model

          $chargeModel = new ChargeModel();
          $chargeModel->setType('SHIPPING')
            ->setAmount(new Currency(array('value' => 0, 'currency' => 'USD')));

          $paymentDefinition->setChargeModels(array($chargeModel));

          $merchantPreferences = new MerchantPreferences();
          // "http://localhost:8080/execute_agreement?success=false"
          // 'http://localhost/talkfluent/api/public/paypal/notify'
          
          $merchantPreferences->setReturnUrl($this->returnUrl)
            ->setCancelUrl($this->cancelUrl)
            ->setAutoBillAmount("yes")
            ->setInitialFailAmountAction("CONTINUE")
            ->setMaxFailAttempts("0")
            ->setSetupFee(new Currency(array('value' => 1, 'currency' => 'USD')));

          $plan->setPaymentDefinitions(array($paymentDefinition));
          $plan->setMerchantPreferences($merchantPreferences);
          try{
              $createdPlan = $plan->create($this->apiContext);
              try {
                $patch = new Patch();
                $value = new PayPalModel('{"state":"ACTIVE"}');
                $patch->setOp('replace')
                  ->setPath('/')
                  ->setValue($value);
                $patchRequest = new PatchRequest();
                $patchRequest->addPatch($patch);
                $createdPlan->update($patchRequest, $this->apiContext);
                $plan = Plan::get($createdPlan->getId(), $this->apiContext);
                $model = new PayPalPlan();
                $insertData = array(
                  'plan'        => $plan->getId(),
                  'name'        => $name,
                  'description' => $description,
                  'state'       => $plan->getState(),
                  'frequency'   => 'Year',
                  'amount'      => ($this->annually * 12),
                  'currency'    => 'USD',
                  'created_at'  => Carbon::now()
                );
                $model->insert($insertData);
                return $plan->getId();
              }catch(\Exception\PayPalConnectionException $ex) {
                echo $ex->getCode();
                echo $ex->getData();
                die($ex);
              }catch(\Exception $ex) {
                die($ex);
              }
              
              // Save ID
          }catch(\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
          }catch(\Exception $ex) {
            die($ex);
          }
        }else{
          return $result[0]['plan'];
        }
    }

    public function createPlanPause($setupAmount, $method){
      $nameFlag = ($method == 'upgrade') ? '' : '(Renewal)';
      $name = 'Talkfluent Pause Account Subscription'.$nameFlag;
      $description = 'Talkfluent Plan';
      $result = PayPalPlan::where('name', '=', $name)->get();
      if(sizeof($result) <= 0){
        $plan = new Plan();
        $plan->setName($name)
        ->setDescription($description)
        ->setType('fixed');

        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Regular Payment')
        ->setType('REGULAR')
        ->setFrequency('MONTH')
        ->setFrequencyInterval('1')
        ->setCycles('1')
        ->setAmount(new Currency(array('value' => ($this->pause), 'currency' => 'USD')));

        $chargeModel = new ChargeModel();
        $chargeModel->setType('SHIPPING')
        ->setAmount(new Currency(array('value' => 0, 'currency' => 'USD')));

        $paymentDefinition->setChargeModels(array($chargeModel));

        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl($this->returnUrl)
        ->setCancelUrl($this->cancelUrl)
        ->setAutoBillAmount("yes")
        ->setInitialFailAmountAction("CONTINUE")
        ->setMaxFailAttempts("0")
        ->setSetupFee(new Currency(array('value' => $setupAmount, 'currency' => 'USD')));

        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);
        try{
          $createdPlan = $plan->create($this->apiContext);
          try{
            $patch = new Patch();
            $value = new PayPalModel('{"state":"ACTIVE"}');
            $patch->setOp('replace')
            ->setPath('/')
            ->setValue($value);

            $patchRequest = new PatchRequest();
            $patchRequest->addPatch($patch);
            $createdPlan->update($patchRequest, $this->apiContext);
            $plan = Plan::get($createdPlan->getId(), $this->apiContext);
            $model = new PayPalPlan();
            $model->plan = $plan->getId();
            $model->name = $name;
            $model->description = $description;
            $model->state = $plan->getState();
            $model->frequency = 'Month';
            $model->amount = $this->pause;
            $model->currency = 'USD';
            $model->created_at = Carbon::now();
            $model->save();
            return $plan->getId();
          }catch(\Exception\PayPalConnectionException $ex) {
              echo $ex->getCode();
            echo $ex->getData();
            die($ex);
          }catch(\Exception $ex) {
            die($ex);
          } 
              // Save ID
        }catch(\Exception\PayPalConnectionException $ex) {
          echo $ex->getCode();
          echo $ex->getData();
          die($ex);
        }catch(\Exception $ex) {
          die($ex);
        }
      }else{
        return $result[0]['plan'];
      }
    }

    public function createPlanMonthly(){
          // Instantiate Plan
          $name = 'Talkfluent Monthly Subscription';
          $description = 'Talkfluent Plan';
          $result = PayPalPlan::where('name', '=', $name)->get();
          if(sizeof($result) <= 0){
              $plan = new Plan();
              $plan->setName($name)
                ->setDescription($description)
                ->setType('fixed');

              // Instantiate Payment Definition
              $paymentDefinition = new PaymentDefinition(); 
              $paymentDefinition->setName('Regular Payments')
                ->setType('REGULAR')
                ->setFrequency('MONTH')
                ->setFrequencyInterval("1")
                ->setCycles("1")
                ->setAmount(new Currency(array('value' => $this->monthly, 'currency' => 'USD')));

              // Add Charge Model

              $chargeModel = new ChargeModel();
              $chargeModel->setType('SHIPPING')
                ->setAmount(new Currency(array('value' => 0, 'currency' => 'USD')));

              $paymentDefinition->setChargeModels(array($chargeModel));

              $merchantPreferences = new MerchantPreferences();

              $merchantPreferences->setReturnUrl($this->returnUrl)
                ->setCancelUrl($this->cancelUrl)
                ->setAutoBillAmount("yes")
                ->setInitialFailAmountAction("CONTINUE")
                ->setMaxFailAttempts("0")
                ->setSetupFee(new Currency(array('value' => 1, 'currency' => 'USD')));

              $plan->setPaymentDefinitions(array($paymentDefinition));
              $plan->setMerchantPreferences($merchantPreferences);
              try{
                  $createdPlan = $plan->create($this->apiContext);
                  try {
                    $patch = new Patch();
                    $value = new PayPalModel('{"state":"ACTIVE"}');
                    $patch->setOp('replace')
                      ->setPath('/')
                      ->setValue($value);
                    $patchRequest = new PatchRequest();
                    $patchRequest->addPatch($patch);
                    $createdPlan->update($patchRequest, $this->apiContext);
                    $plan = Plan::get($createdPlan->getId(), $this->apiContext);
                    $model = new PayPalPlan();
                    $insertData = array(
                      'plan'        => $plan->getId(),
                      'name'        => $name,
                      'description' => $description,
                      'state'       => $plan->getState(),
                      'frequency'   => 'Month',
                      'amount'      => ($this->monthly),
                      'currency'    => 'USD',
                      'created_at'  => Carbon::now()
                    );
                    $model->insert($insertData);
                    return $plan->getId();
                  }catch(\Exception\PayPalConnectionException $ex) {
                    echo $ex->getCode();
                    echo $ex->getData();
                    die($ex);
                  }catch(\Exception $ex) {
                    die($ex);
                  }
                  
                  // Save ID
              }catch(\Exception\PayPalConnectionException $ex) {
                echo $ex->getCode();
                echo $ex->getData();
                die($ex);
              }catch(\Exception $ex) {
                die($ex);
              }
          }else{
            return $result[0]['plan'];
          }
        }

        public function redirectPause($accountId, $name, $config, $method){
          $this->init($config['paypal']);
          $nameFlag = ($method == 'upgrade') ? 'Upgrade' : 'Renew';

          $startDate = null;
          if($method == 'upgrade'){
            $startDate = Carbon::now()->addDay($this->trialPeriod)->toIso8601String();
          }else{
            $startDate = Carbon::now()->addMonth()->toIso8601String();
          }

          $agreement = new Agreement();
          $agreement->setName('Talkfluent Pause Account Subscription US$'.$this->pause.' '.$nameFlag)
          ->setDescription('This subscription is for the Monthly package of Talkfluent, amount to US$'.$this->pause.' per month')->setStartDate($startDate);

          $plan = new Plan();
          $setupAmount = ($method == 'upgrade') ? 0 : $this->pause;
          $planId = $this->createPlanPause($setupAmount, $method);
          $plan->setId($planId);

          $agreement->setPlan($plan);

          $payer = new Payer();
          $payer->setPaymentMethod('paypal');
          $agreement->setPayer($payer);

          $shippingAddress = new ShippingAddress();

          try{
            $agreement = $agreement->create($this->apiContext);
            $_creation = new AccountPayPalCreation();
            $_creation->account_id = $accountId;
            $_creation->paypal_plan = $planId;
            $_creation->method = $method;
            $_creation->plan = 'pause';
            $_creation->name = $name;
            $_creation->amount = $this->pause * 100;
            $_creation->description_amount = 'US$ '.$this->pause. 'per month';
            $_creation->client_ip = (isset($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP'] : 'empty';
            $_creation->remote_address = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'empty';
            $_creation->address = (isset($_SERVER['HTTP_X_FORWARDE‌​D_FOR'])) ? $_SERVER['HTTP_X_FORWARDE‌​D_FOR'] : 'empty';
            $_creation->created_at = Carbon::now();
            $_creation->save();
              $this->approvalUrl = $agreement->getApprovalLink();
          } catch (\Exception\PayPalConnectionException $ex) {
              echo $ex->getCode();
              echo $ex->getData();
              die($ex);
          } catch (\Exception $ex) {
              die($ex);
          }
        }

        public function redirectAnnuallyWithoutSetupFee($accountId, $paypalName, $config, $method){
          $this->init($config['paypal']);
          $nameFlag = ($method == 'upgrade')? 'Upgrade' : 'Renew';

          $annualFee = ($this->annually * 12);
          $monthlyFee = $this->annually;

          $startDate = null;
          if($method == 'upgrade'){
            $startDate = Carbon::now()->addDay($this->trialPeriod)->toIso8601String();
          }else{
            $startDate = Carbon::now()->addYear()->toIso8601String();
          }
          $name = 'Talkfluent Annualy Subscription US$'.$annualFee.' '.$nameFlag;
          $description = 'This subscription is for the Annually package of Talkfluent, amounting to US$'.$monthlyFee.' per month.';

          $agreement = new Agreement();
          $agreement->setName($name)
                    ->setDescription($description)
                    ->setStartDate($startDate);

          $plan = new Plan();
          $setupAmount = ($method == 'upgrade') ? 0 : $annualFee;
          $planId = $this->createPlanAnnuallyWithoutSetupFee($setupAmount, $method);
          $plan->setId($planId);

          $agreement->setPlan($plan);

          $payer = new Payer();
          $payer->setPaymentMethod('paypal');
          $agreement->setPayer($payer);

          try {
              // Create agreement on Paypal
              $agreement = $agreement->create($this->apiContext);
              $_creation = new AccountPayPalCreation();
              $_creation->account_id = $accountId;
              $_creation->paypal_plan = $planId;
              $_creation->method = $method;
              $_creation->plan = 'annually';
              $_creation->amount = ''.($this->annually * 12 * 100);
              $_creation->description_amount = 'US$'.($this->annually * 12).' per year';
              $_creation->name = $paypalName;
              $_creation->client_ip = (isset($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP'] : 'empty';
              $_creation->remote_address = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'empty';
              $_creation->address = (isset($_SERVER['HTTP_X_FORWARDE‌​D_FOR'])) ? $_SERVER['HTTP_X_FORWARDE‌​D_FOR'] : 'empty';
              $_creation->created_at = Carbon::now();
              $_creation->save();
              $this->approvalUrl = $agreement->getApprovalLink();
          }catch(\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
          }catch(\Exception $ex) {
            die($ex);
          }
        }



    public function redirectMonthlyWithoutSetupFee($accountId, $name, $config, $method){
      $this->init($config['paypal']);
      $nameFlag = ($method == 'upgrade') ? 'Upgrade' : 'Renew';

      $startDate = null;
      if($method == 'upgrade'){
        $startDate = Carbon::now()->addDay($this->trialPeriod)->toIso8601String();
      }else{
        $startDate = Carbon::now()->addMonth()->toIso8601String();
      }

      // Instanstiate Agreement
      $agreement = new Agreement();
      $agreement->setName('Talkfluent Monthly Subscription US$'.$this->monthly.' '.$nameFlag)
        ->setDescription('This subscription is for the Monthly package of Talkfluent, amounting to US$'.$this->monthly.' per month.')
        ->setStartDate($startDate);

      $plan = new Plan();
      $setupAmount = ($method == 'upgrade') ? 0 : $this->monthly;
      $planId = $this->createPlanMonthlyWithoutSetupFee($setupAmount, $method);
      $plan->setId($planId);

      $agreement->setPlan($plan);

      $payer = new Payer();
      $payer->setPaymentMethod('paypal');
      $agreement->setPayer($payer);

      $shippingAddress = new ShippingAddress();

      try {
        $agreement = $agreement->create($this->apiContext);
        $_creation = new AccountPayPalCreation();
        $_creation->account_id = $accountId;
        $_creation->paypal_plan = $planId;
        $_creation->method = $method;
        $_creation->plan = 'monthly';
        $_creation->amount = ''.($this->monthly * 100);
        $_creation->description_amount = 'US$'.$this->monthly.' per month';
        $_creation->name = $name;
        $_creation->client_ip = (isset($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP'] : 'empty';
        $_creation->remote_address = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'empty';
        $_creation->address = (isset($_SERVER['HTTP_X_FORWARDE‌​D_FOR'])) ? $_SERVER['HTTP_X_FORWARDE‌​D_FOR'] : 'empty';
        $_creation->created_at = Carbon::now();
        $_creation->save();
        $this->approvalUrl = $agreement->getApprovalLink();
      } catch (\Exception $ex) {
        die($ex);
      } catch(\Exception\PayPalConnectionException $ex){
        echo json_encode($ex->getData());
        echo json_encode($ex->getCode());
        die($ex);
      }

    }

     public function redirectAnnually($accountId, $paypalName, $config){
      $this->init($config['paypal']);
      // $annualFee = $data['config']['annually']['total'];
      // $monthlyFee = $data['config']['annually']['monthly'];
      $annualFee = ($this->annually * 12);
      $monthlyFee = $this->annually;

      $startDate = Carbon::now()->addDay($this->trialPeriod)->toIso8601String();
      $name = 'Talkfluent Annual Subscription US$'.$annualFee.'.';
      $description = 'This subscription is for the Annually package of Talkfluent, amounting to US$'.$monthlyFee.' per month.';
      // Instantiate Agreement
      $agreement = new Agreement();
      $agreement->setName($name)
                ->setDescription($description)
                ->setStartDate($startDate);

      // Instantiate Plan
      $plan = new Plan();
      $planId = $this->createPlanAnnually();
      $plan->setId($planId);
      // Set Plan
      $agreement->setPlan($plan);


      // Add payer type
      $payer = new Payer();
      $payer->setPaymentMethod('paypal');
      $agreement->setPayer($payer);


      $shippingAddress = new ShippingAddress();
      // $shippingAddress->setLine1($data['billing']['address'])
      //     ->setCity($data['billing']['city'])
      //     ->setState($data['billing']['state'])
      //     ->setPostalCode($data['billing']['postal_code'])
      //     ->setCountryCode($data['billing']['country']);
      // $agreement->setShippingAddress($shippingAddress);   
      try {
          // Create agreement on Paypal
          $agreement = $agreement->create($this->apiContext);
          $_creation = new AccountPayPalCreation();
          $_creation->account_id = $accountId;
          $_creation->paypal_plan = $planId;
          $_creation->method = 'create';
          $_creation->plan = 'annually';
          $_creation->amount = '100';
          $_creation->description_amount = 'US$1 for 7 Days Trial Period';
          $_creation->name = $paypalName;
          $_creation->client_ip = (isset($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP'] : 'empty';
          $_creation->remote_address = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'empty';
          $_creation->address = (isset($_SERVER['HTTP_X_FORWARDE‌​D_FOR'])) ? $_SERVER['HTTP_X_FORWARDE‌​D_FOR'] : 'empty';
          $_creation->created_at = Carbon::now();
          $_creation->save();
          $this->approvalUrl = $agreement->getApprovalLink();
      }catch(\Exception\PayPalConnectionException $ex) {
        echo $ex->getCode();
        echo $ex->getData();
        die($ex);
      }catch(\Exception $ex) {
        die($ex);
      }
    }

    public function redirectMonthly($accountId, $name, $config){
      $this->init($config['paypal']);

      // Instantiate Agreement
      $agreement = new Agreement();
      $agreement->setName('Talkfluent Monthly Subscription US$'.$this->monthly.'.')
                ->setDescription('This subscription is for the Monthly package of Talkfluent, amounting to US$'.$this->monthly.' per month.')
                ->setStartDate(Carbon::now()->addDay($this->trialPeriod)->toIso8601String());

      // Instantiate Plan
      $plan = new Plan();
      $planId = $this->createPlanMonthly();
      $plan->setId($planId);
      // Set Plan
      $agreement->setPlan($plan);

      // Add payer type
      $payer = new Payer();
      $payer->setPaymentMethod('paypal');
      $agreement->setPayer($payer);

      $shippingAddress = new ShippingAddress();
      // $shippingAddress->setLine1($data['billing']['address'])
      //     ->setCity($data['billing']['city'])
      //     ->setState($data['billing']['state'])
      //     ->setPostalCode($data['billing']['postal_code'])
      //     ->setCountryCode($data['billing']['country']);
      // $agreement->setShippingAddress($shippingAddress);      

      try {
          // Create agreement on Paypal
          $agreement = $agreement->create($this->apiContext);
          // Extract approval URL to redirect user
          $_creation = new AccountPayPalCreation();
          $_creation->account_id = $accountId;
          $_creation->paypal_plan = $planId;
          $_creation->method = 'create';
          $_creation->plan = 'monthly';
          $_creation->name = $name;
          $_creation->amount = '100';
          $_creation->description_amount = 'US$1 for 7 Days of Trial Period';
          $_creation->client_ip = (isset($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP'] : 'empty';
          $_creation->remote_address = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'empty';
          $_creation->address = (isset($_SERVER['HTTP_X_FORWARDE‌​D_FOR'])) ? $_SERVER['HTTP_X_FORWARDE‌​D_FOR'] : 'empty';
          $_creation->created_at = Carbon::now();
          $_creation->save();
          $this->approvalUrl = $agreement->getApprovalLink();
      } catch (\Exception\PayPalConnectionException $ex) {
          echo $ex->getCode();
          echo $ex->getData();
          die($ex);
      } catch (\Exception $ex) {
          die($ex);
      }
    }

    public function executeAgreement(Request $request){
      // Note to notify user for the agreement using the email address
      $clientIp = (isset($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP'] : 'empty';
      $remoteAddress = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'empty';
      $forwarder = (isset($_SERVER['HTTP_X_FORWARDE‌​D_FOR'])) ? $_SERVER['HTTP_X_FORWARDE‌​D_FOR'] : 'empty';
      $accountPaypal = AccountPayPalCreation::where('address', '=', $forwarder)->where('remote_address', '=', $remoteAddress)->where('client_ip', '=', $clientIp)->orderBy('created_at', 'DESC')->get();
      $accountId = (sizeof($accountPaypal) > 0) ? $accountPaypal[0]['account_id'] : null;
      $paypalName = (sizeof($accountPaypal) > 0) ? $accountPaypal[0]['name'] : null;
      $method = (sizeof($accountPaypal) > 0) ? $accountPaypal[0]['method'] : null;
      $amount = (sizeof($accountPaypal) > 0) ? $accountPaypal[0]['amount'] : null;
      $newPlan = (sizeof($accountPaypal) > 0) ? $accountPaypal[0]['plan'] : null;
      $descriptionAmount = (sizeof($accountPaypal) > 0) ? $accountPaypal[0]['description_amount'] : null;
      $token = $this->extractToken($request->fullUrl());
      $account = $this->getAccount($accountId);
      $this->init($this->getConfigByExecute($request->fullUrl()));
      $planResult = null;

      $planId = (sizeof($accountPaypal) > 0) ? $accountPaypal[0]['paypal_plan'] : null;
      
      $planResult = PayPalPlan::where('plan', '=', $planId)->get();
      
      $dataRequest = $request->all();
      if($planId != null && sizeof($planResult) > 0 && isset($dataRequest['token'])){
        $agreement = new \PayPal\Api\Agreement();
        $plan = new Plan();
        $plan->setId($planId);
        $agreement->setPlan($plan);

        try{
            $result = $agreement->execute($dataRequest['token'], $this->apiContext);
            if(isset($result->id)){
              $_agreementResult = PayPalAgreement::where('agreement', '=', $result->id)->get();
              if(sizeof($_agreementResult) <= 0){

               AccountPaymentMethod::where('account_id', '=', $accountId)->update(array(
                  'status'  => 'inactive'
                ));
               
                $_paypal_account = null;
                if($method == 'create' || $method == 'recreate'){
                  $_paypal_account = new PayPalAccount();
                  $_paypal_account->account_id = $accountId;
                  $_paypal_account->name = $paypalName;
                  $_paypal_account->created_at = Carbon::now();
                  $_paypal_account->save();
                }else{
                  $_paypal_account = PayPalAccount::where('account_id', '=', $accountId)->first();
                }

                $_agreement = new PayPalAgreement();
                $_agreement->account_id = $accountId;
                $_agreement->paypal_account_id = $_paypal_account->id;
                $_agreement->agreement = $result->id;
                $_agreement->state = $result->state;
                $_agreement->token = $token;
                $_agreement->description = $result->description;
                $_agreement->start_date = $result->start_date;
                $_agreement->created_at = Carbon::now();
                $_agreement->save();

                $_paymentMethod = new AccountPaymentMethod();
                $_paymentMethod->account_id = $accountId;
                $_paymentMethod->method = 'paypal';
                $_paymentMethod->status = 'active';
                $_paymentMethod->source = $_paypal_account->id;
                $_paymentMethod->created_at = Carbon::now();
                $_paymentMethod->save();

                $billingDescriptionAmount = ($method == 'create') ? 'US$1.00 for 7 days' : 'US$0.00 for 7 days';
                $billingDescription = null;

                if($method == 'create'){
                  $billingDescription = 'Trial Period';
                }else if($method == 'recreate'){
                  $billingDescription = 'Renew Account';
                }else{
                  $billingDescription = 'Upgrade Account';
                }

                if($method == 'create'){
                  $_billing = new Billing();
                  $_billing->account_id = $accountId;
                  $_billing->coupon_id = null;
                  $_billing->status = 'created';
                  $_billing->start_date = Carbon::now();
                  $_billing->end_date = Carbon::now()->addDay(7);
                  $_billing->payment_method = 'paypal';
                  $_billing->currency = 'USD';
                  $_billing->description = 'US$1.00 for 7 days';
                  $_billing->description_amount = $descriptionAmount;
                  $_billing->total_amount = $amount;
                  $_billing->taxes_and_fees = 0;
                  $_billing->discount_total_amount = 0;
                  $_billing->created_at = Carbon::now();
                  $_billing->save();


                  // send email here

                  $billingDetails = array(
                    'description' => 'US$1.00 for 7 days Trial',
                    'unit_price'  => 1.00,
                    'qty'         => 1,
                    'amount'      => 1.00,
                    'subtotal'    => 1.00,
                    'tax'         => 0,
                    'discount'    => 0,
                    'total'       => 1.00
                  );

                  $returnUrl = app('App\Http\Controllers\EmailController')->receipt($accountId, null, $billingDetails);

                  $startDate = $this->getDateTime($result->start_date);
                  $_billing = new Billing();
                  $_billing->account_id = $accountId;
                  $_billing->coupon_id = null;
                  $_billing->status = 'created';
                  $_billing->start_date = $startDate;
                  $_billing->end_date = ($newPlan == 'annually') ? Carbon::createFromFormat('Y-m-d H:i:s', $startDate)->addYear() : Carbon::createFromFormat('Y-m-d H:i:s', $startDate)->addMonth();
                  $_billing->payment_method = 'paypal';
                  $_billing->currency = 'USD';
                  $_billing->description = $planResult[0]['name'];
                  $_billing->description_amount = $planResult[0]['description'];
                  $_billing->total_amount = intval($planResult[0]['amount']) * 100;
                  $_billing->taxes_and_fees = 0;
                  $_billing->discount_total_amount = 0;
                  $_billing->created_at = Carbon::now()->addSecond();
                  $_billing->save();
                }else if($method == 'recreate'){
                  $this->updateBilling($accountId);
                  $_billing = new Billing();
                  $_billing->account_id = $accountId;
                  $_billing->coupon_id = null;
                  $_billing->status = 'succeeded';
                  $endDate = ($newPlan == 'annually') ? Carbon::now()->copy()->addYear() : Carbon::now()->copy()->addMonth();
                  $_billing->start_date = Carbon::now();
                  $_billing->end_date = $endDate;
                  $_billing->payment_method = 'paypal';
                  $_billing->currency = 'USD';
                  $_billing->description = $billingDescription;
                  $_billing->description_amount = $descriptionAmount;
                  $_billing->total_amount = $amount;
                  $_billing->taxes_and_fees = 0;
                  $_billing->discount_total_amount = 0;
                  $_billing->created_at = Carbon::now();
                  $_billing->save();


                  // send email here

                  $billingDetails = array(
                    'description' => $billingDescription,
                    'unit_price'  => $amount,
                    'qty'         => 1,
                    'amount'      => $amount,
                    'subtotal'    => $amount,
                    'tax'         => 0,
                    'discount'    => 0,
                    'total'       => $amount
                  );

                  $returnUrl = app('App\Http\Controllers\EmailController')->receipt($accountId, null, $billingDetails);

                  // send email here
                }else if($method == 'upgrade'){
                  $this->updateBilling($accountId);
                  $_billing = new Billing();
                  $_billing->account_id = $accountId;
                  $_billing->coupon_id = null;
                  $_billing->status = 'created';
                  $startDate = $this->getDateTime($result->start_date);
                  $endDate = ($newPlan == 'annually') ? Carbon::createFromFormat('Y-m-d H:i:s', $startDate)->addYear() : Carbon::createFromFormat('Y-m-d H:i:s', $startDate)->addMonth();
                  $_billing->start_date = $this->getDateTime($result->start_date);
                  $_billing->end_date = $endDate;
                  $_billing->payment_method = 'paypal';
                  $_billing->currency = 'USD';
                  $_billing->description = $billingDescription;
                  $_billing->description_amount = $descriptionAmount;
                  $_billing->total_amount = $amount;
                  $_billing->taxes_and_fees = 0;
                  $_billing->discount_total_amount = 0;
                  $_billing->created_at = Carbon::now();
                  $_billing->save();
                }

                if($newPlan == 'pause'){
                  Account::where('id', '=', $accountId)->update(
                    array(
                      'payment_status' => 'active',
                      'paused_on' => $this->getDateTime($result->start_date),
                      'updated_at'  => Carbon::now(),
                      'canceled_on' => null
                  ));
                }else{
                  Account::where('id', '=', $accountId)->update(
                    array(
                      'plan'  => $newPlan,
                      'payment_status' => 'active',
                      'canceled_on' => null,
                      'paused_on' => null
                  ));
                }

                $updateData = array(
                  'updated_at'  => Carbon::now(),
                  'deleted_at'  => Carbon::now()
                );

                AccountPayPalCreation::where('id', '=', $accountPaypal[0]['id'])->update($updateData);
              }
              return redirect($this->returnUrl);
            }else{
              echo json_encode($result);
            }
            // save billing
            // Save payment method
        } catch (\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (\Exception $ex) {
            die($ex);
        }
    }
  }
  public function testAgreement(Request $request){
    $url =  $request->fullUrl();
    $parameter = $this->extract($url);
    // $token = explode('=', $parameter[1]);
    echo $this->extractToken($url);
    echo json_encode($parameter);
    // echo $token[1];
  }

  public function getConfigByExecute($url){
    $array = explode('/', $url);
    if($array[2] == 'localhost'){
      return array(
        'mode'  => 'sandbox',
        'return_url'  => 'http://localhost:8080/#/dashboard',
        'cancel_url'  => 'http://localhost:8080/#/my_plan'
      );
    }else{
      return array(
        'mode'  => 'sandbox',
        'return_url'  => 'https://app.talkfluentspanish.com/#/dashboard',
        'cancel_url'  => 'https://app.talkfluentspanish.com/#/my_plan'
      );
    }
  }

  public function extractToken($url){
    $parameter = $this->extract($url);
    $token = explode('=', $parameter[1]);
    return $token[1];
  }

  public function extractAccountId($url){
    $parameter = $this->extract($url);
    return $parameter[0];
  }

  public function extract($url){
    $array = explode('/', $url);
    $parameter = null;
    $i = 0;
    foreach ($array as $key) {
      if(strpos($array[$i], '?')){
        $parameter = explode('?', $array[$i]);
      }
      $i++;
    }
    return $parameter;
  }

  public function accountStatus(Request $request){

  }

  public function cancelBillingPlan(Request $request){
    // delete current row
    // update account table
    $data = $request->all();
    $this->init($data['config']['paypal']);
    $account = Account::where('id', '=', $data['account_id'])->first();
    $agreement = PayPalAgreement::where('account_id', '=', $data['account_id'])->first();
    $billing = Billing::where('account_id', '=', $data['account_id'])->first();
    if($agreement && $billing){
      $_agreement = new Agreement();
      $_agreement->setId($agreement->agreement);
      try{
        $details = $_agreement->get($agreement->agreement, $this->apiContext);
        $plan = json_decode($details->getPlan(), true);
        $amount = $plan['payment_definitions'][0]['amount']['value'];
        if($details && $details->state == 'Active'){
          $this->cancelOnPaypalAgreement($agreement->agreement, $amount);
        }else{
          return response()->json(array(
            'data'  => false,
            'error' => 'Unable to Cancel your subsctiption due to the state of your paypal agreement is still pending.',
            'timestamp' => Carbon::now()
          ));
        }
      
        $feedback = new FeedBack();
        $feedback->account_id = $data['account_id'];
        $feedback->message = $data['message'];
        $feedback->created_at = Carbon::now();
        $feedback->save();
        $date1 = Carbon::createFromFormat('Y-m-d H:i:s', $this->getDateTime($details->agreement_details->next_billing_date))->startOfDay();
        $date2 = Carbon::now()->startOfDay();
        $diff = $date2->diffInDays($date1);

        if($diff > 0){
          Account::where('id', '=', $data['account_id'])->update(
            array(
              'canceled_on'  => $this->getDateTime($details->agreement_details->next_billing_date),
              'updated_at'      => Carbon::now()
          ));
        }else{
          Account::where('id', '=', $data['account_id'])->update(
            array(
              'payment_status'  => 'failed',
              'canceled_on'     => $this->getDateTime($details->agreement_details->next_billing_date),
              'updated_at'      => Carbon::now()
          ));
        }
        $this->updateBilling($data['account_id']);
        PayPalAgreement::where('account_id', '=', $data['account_id'])->update(
          array(
            'deleted_at'      => Carbon::now()
        ));

        return response()->json(
          array(
            'data'  => true,
            'error' => null,
            'timestamp' => Carbon::now()
          )
        );
      }catch(\Exception\PayPalConnectionException $ex) {
        echo $ex->getCode();
        echo $ex->getData();
        die($ex);
      }catch(\Exception $ex) {
        echo $ex->getCode();
        // echo $ex->getData();
        die($ex);
      }
    }else{
       return response()->json(
        array(
          'data'  => false,
          'error' => 'Unable to Cancel your subsctiption. Please contact the administrator.',
          'timestamp' => Carbon::now()
        )
        );
    }
  }

  public function cancelAgreementByAccountId($accountId){
    $agreement = PayPalAgreement::where('account_id', '=', $accountId)->where('state', '=', 'Active')->orderBy('created_at', 'desc')->get();

    if(sizeof($agreement) > 0){
      // get the last payment also
      $lastPayment = Billing::where("account_id", "=", $accountId)->where('payment_method', '=', 'paypal')->orderBy('created_at', 'desc')->get();
      if($lastPayment && sizeof($lastPayment) > 0){
        // call cancelAgreement
        $this->cancelOnPaypalAgreement($agreement[0]['agreement'], $lastPayment[0]['total_amount']);
      }
      return true;
    }else{
      return false;
    }
  }

  public function cancelOnPaypalAgreement($agreementId, $amount){
    $agreement = new Agreement();
    $agreement->setId($agreementId);
    $stateDescriptor = new AgreementStateDescriptor();
    $stateDescriptor->setNote('Cancel Plan');
    $stateDescriptor->setAmount(new Currency(array(
      'value' =>  $amount,
      'currency'  => 'USD'
    )));
    try{
      $result = $agreement->cancel($stateDescriptor, $this->apiContext);
    }catch(\Exception\PayPalConnectionException $ex) {
      echo $ex->getCode();
      echo $ex->getData();
      die($ex);
    }catch(\Exception $ex) {
      echo $ex->getCode();
      // echo $ex->getData();
      die($ex);
    }
  }

  public function cancelAgreement(Request $request){
    // delete current row
    // update account table
    $clientIp = (isset($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP'] : 'empty';
    $remoteAddress = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'empty';
    $forwarder = (isset($_SERVER['HTTP_X_FORWARDE‌​D_FOR'])) ? $_SERVER['HTTP_X_FORWARDE‌​D_FOR'] : 'empty';
    $accountPaypal = AccountPayPalCreation::where('address', '=', $forwarder)->where('remote_address', '=', $remoteAddress)->where('client_ip', '=', $clientIp)->orderBy('created_at', 'DESC')->get();
    $this->init($this->getConfigByExecute($request->fullUrl()));
    $token = $this->extractToken($request->fullUrl());
    if(sizeof($accountPaypal) > 0){
      $accountId = $accountPaypal[0]['account_id'];
      $this->updateBilling($accountId);
      $id = $accountPaypal[0]['id'];
      Account::where('id', '=', $accountId)->update(array(
        'payment_status'  => 'failed'
      ));

      AccountPaymentMethod::where('account_id', '=', $accountId)->update(array(
        'deleted_at'  => Carbon::now()
      ));

      AccountPayPalCreation::where('id', '=', $id)->update(array(
        'deleted_at' => Carbon::now()
      ));
    }
    return redirect($this->cancelUrl);
  }
}


