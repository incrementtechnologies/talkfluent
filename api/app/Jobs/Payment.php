<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Account;
use App\Logger;
use App\PayPalAccount;
use App\PayPalAgreement;
use App\AccountPaymentMethod;
use App\CreditCard;
use Carbon\Carbon;
class Payment implements ShouldQueue
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
        echo ("\nExecuting Billing Manager Console...");
        $date = Carbon::now()->toDateString();
        $accounts = Account::whereDate('canceled_on', '=', $date)->where('payment_status', '!=', 'failed')->orderBy('canceled_on', 'ASC')->get();
        echo $date;
        if(sizeof($accounts) > 0){
            $i = 0;
            foreach ($accounts as $key) {
                $current = Carbon::now();
                $end = Carbon::createFromFormat('Y-m-d H:i:s', $accounts[$i]['canceled_on']);
                echo ("\nCurrent Date:" .$current);
                echo ("\nAccount Date:" .$end);
                $diff = $current->diffInSeconds($end, false);
                echo ("\nDate Difference:" .$diff);
                if($diff <= 0 && $accounts[$i]['payment_status'] != 'failed'){
                    //Update Account
                    $paymentMethod = AccountPaymentMethod::where('account_id', '=', $accounts[$i]['id'])->first();


                    if($paymentMethod->method == 'paypal'){

                        echo ("\nAccount Payment Method Updated...");
                        AccountPaymentMethod::where('account_id', '=', $accounts[$i]['id'])->update(
                            array(
                                'deleted_at'    => Carbon::now()
                            )
                        );

                        $paypalAccount = PayPalAccount::where('account_id', '=', $accounts[$i]['id'])->first();
                        echo ("\nPayPal Account Updated...");
                        PayPalAccount::where('account_id', '=', $accounts[$i]['id'])->update(
                            array(
                                'deleted_at'    => Carbon::now()
                            )
                        );
                    }else if($paymentMethod->method == 'stripe'){
                        echo ("\nAccount Payment Method Updated...");
                        AccountPaymentMethod::where('account_id', '=', $accounts[$i]['id'])->update(
                            array(
                                'deleted_at'    => Carbon::now()
                            )
                        );

                        CreditCard::where('id', '=', $paymentMethod->source)->update(array(
                            'deleted_at'    => Carbon::now()
                        ));
                    }
                    Account::where('id', '=', $accounts[$i]['id'])->update(
                        array(
                            'payment_status'    => 'failed',
                            'updated_at'        => Carbon::now()
                        )
                    );
                    $message = "Username:".$accounts[$i]['username']." and email:".$accounts[$i]['email']." account was canceled and payment_status set to failed on ".Carbon::now()->toDayDateTimeString();
                    echo ("\n".$message);
                    $logger = new Logger();
                    $logger->account_id = $accounts[$i]['id'];
                    $logger->payload = "billing";
                    $logger->message = $message;
                    $logger->save();
                    if($logger->id){
                        echo "\nLogger executed";
                    }else{
                        echo "\nLogger failed";
                    }
                }else{
                    //
                }
                $i++;
            }
            echo ("\nBilling Manager Console Finished!");
        }else{
            echo ("\nEmpty");
        }
    }
}
