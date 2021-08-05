<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Billing;
use App\Account;
use App\StripeSubscription;
use App\StripeWebhooks;
use Carbon\Carbon;
class BillingMonitoring implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct()
  {
      //
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $accounts = Account::get();

    if($accounts && sizeof($accounts) > 0){
      foreach ($accounts as $key => $account) {
        
        $lastBilling = Billing::where('account_id', '=', $account['id'])->orderBy('end_date', 'desc')->limit(1)->get();
        echo "\n\n Running on account id => ".$account['id'];
        if($lastBilling && sizeof($lastBilling) > 0){
          $billing = $lastBilling[0];
          $currentDate = Carbon::now();
          $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $billing['end_date']);
          $diffInSeconds = $currentDate->diffInSeconds($endDate, false);
          echo "\n\t Time difference in seconds => ".$diffInSeconds;
          if($diffInSeconds < 0){
            //check the payment method
            if($billing['payment_method'] == 'credit_card'){
              // check via stripe
              $this->manageStripe($billing, $account);
            }else{
              // check via paypal
              $this->managePayPal($billing, $account);
            }
          }else{
            echo "\n\t [BillingMonitoring] Billing end date is still active";
          }
        }else{
          echo "\n\t [BillingMonitoring] ended => no billing added";
        }
      }
    }else{
      echo "\n [BillingMonitoring] ended";
    }
  }

  public function manageStripe($billing, $account){
    echo "\n\t\t Checking on stripe";
    $subscription = StripeSubscription::where('account_id', '=', $account['id'])->orderBy('created_at', 'desc')->limit(1)->get();

    if($subscription && sizeof($subscription) > 0){
      $subscription = $subscription[0];
      $stripe = new StripeWebhooks(env('STRIPE_PK'), env('STRIPE_SK'));
      $lastDate = intval($stripe->retrieveSubscriptionCurrentEndDate($subscription['subscription']));
      $currentTime = idate('U');
      $diff = $lastDate - $currentTime;

      echo "\n\t\t stripe time difference ".$diff;
      if($diff > 0){
        echo "\n\t\t Do nothing, active subscription status";
      }else{
        echo "\n\t\t add to billing history";
        $this->addToBilling($billing, $account);
      }
    }else{
      echo "\n\t\t No subscription found";
    }
  }

  public function managePayPal(){
    echo "\n\t\t Checking on paypal";
  }

  public function addToBilling($billing, $account){
    $monthly = 4900;
    $yearly = 22800;
    $trial= 100;

    $startDate = $billing['start_date'];
    $endDate = $billing['end_date'];
    $billing['start_date'] = $endDate;
    $endDateFormatted = Carbon::createFromFormat('Y-m-d H:i:s', $endDate);

    if($billing['total_amount'] == $trial){
      if($account['plan'] == 'monthly'){
        $billing['description'] = 'Monthly Plan';
        $billing['description_amount'] = 'US$49 per month';
        $billing['total_amount'] = 4900;
        $billing['end_date'] = $endDateFormatted->copy()->addMonth(1);
      }else{
        $billing['description'] = 'Yearly Plan';
        $billing['description_amount'] = 'US$228 per year';
        $billing['total_amount'] = 22800;
        $billing['end_date'] = $endDateFormatted->copy()->addYear(1);
      }
    }else if($billing['total_amount'] == $monthly){
      $billing['description'] = 'Monthly Plan';
      $billing['description_amount'] = 'US$49 per month';
      $billing['total_amount'] = 4900;
      $billing['end_date'] = $endDateFormatted->copy()->addMonth(1);
    }else if($billing['total_amount'] == $monthly){
      $billing['description'] = 'Yearly Plan';
      $billing['description_amount'] = 'US$228 per year';
      $billing['total_amount'] = 22800;
      $billing['end_date'] = $endDateFormatted->copy()->addYear(1);
    }

    $billingData = array(
      'start_date' => $billing['start_date'],
      'end_date'   => $billing['end_date'],
      'account_id' => $billing['account_id'],
      'payment_method' => $billing['payment_method'],
      'coupon_id' => null,
      'currency'   => $billing['currency'],
      'description' => $billing['description'],
      'description_amount' => $billing['description_amount'],
      'total_amount' => $billing['total_amount'],
      'taxes_and_fees' => $billing['taxes_and_fees'],
      'discount_total_amount' => $billing['discount_total_amount'],
      'status' => $billing['status'],
      'created_at' => Carbon::now()
    );

    Billing::insert($billingData);
  }
}
