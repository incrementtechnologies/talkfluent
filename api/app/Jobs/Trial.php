<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Account;
use App\Logger;
use Carbon\Carbon;
class Trial implements ShouldQueue
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
        // get the trial accounts
        // check if date is 7 days already
        echo ("\nExecuting Trial Accounts Manager Console...");
        $trialAccounts = Account::where('status', '=', 'trial')->orderBy('created_at', 'asc')->get();
        if(sizeof($trialAccounts) > 0){
            $i = 0;
            foreach ($trialAccounts as $key) {
                $accountId = $trialAccounts[$i]['id'];
                $current = Carbon::now();
                $accountDate = Carbon::createFromFormat('Y-m-d H:i:s', $trialAccounts[$i]['created_at']);
                
                $diff = $accountDate->diffInDays($current, false);
                echo ("\nCurrent Date:".$current);
                echo ("\nAccount Date:". $accountDate);
                echo ("\nDiff in Days:". $diff);
                if($diff >= 7){
                    Account::where('id', '=', $accountId)->update(
                        array(
                            'status'    => 'regular',
                            'updated_at'    => Carbon::now()
                        )
                    );
                    $message = "Trial period of ".$trialAccounts[$i]['username']." was ended on ".Carbon::now()->toDayDateTimeString()." created at".$trialAccounts[$i]['created_at'];
                    $logger = new Logger();
                    $logger->account_id = $accountId;
                    $logger->payload = 'trial';
                    $logger->message = $message;
                    $logger->save();
                    echo ("\n".$message);
                }
                $i++;
            }
            echo ("\nTrial Accounts Manager Finished");
        }else{
            $logger = new Logger();
            $logger->payload = "trial";
            $logger->message = "No Trial Accounts on ".Carbon::now()->toDayDateTimeString();
            $logger->save();
            echo ("\nTrial Jobs Finished: Empty Trial Accounts");
        }
    }
}
