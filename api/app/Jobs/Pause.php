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
class Pause implements ShouldQueue
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
        echo ("\nExecuting Pause Manager Console...");
        $date = Carbon::now()->toDateString();
        $accounts = Account::whereDate('paused_on', '=', $date)->where('plan', '!=', 'pause')->get();

        if(sizeof($accounts) > 0){
            $i = 0;
            foreach ($accounts as $key) {
                $current = Carbon::now();
                $accountDate = Carbon::createFromFormat('Y-m-d H:i:s', $accounts[$i]['paused_on']);
                $diff = $current->diffInSeconds($accountDate, false);
                echo ("\nCurrent Date:".$current);
                echo ("\nAccount Date:". $accountDate);
                echo ("\nDiff in Days:". $diff);

                if($diff <= 0){
                    Account::where('id', '=', $accounts[$i]['id'])->update(
                        array(
                            'plan'  => 'pause',
                            'updated_at'    => Carbon::now()
                        )
                    );
                    $message = "Pause Account Plan of ".$accounts[$i]['username']." was made effective on ".Carbon::now()->toDayDateTimeString();
                    $logger = new Logger();
                    $logger->account_id = $accounts[$i]['id'];
                    $logger->payload = 'pause';
                    $logger->message = $message;
                    $logger->save();
                    echo ("\n".$message);
                }
                $i++;
            }
            echo ("\nPause Manager Console Finished.");
        }else{
            echo ("\nEmpty");
        }
        $logger = new Logger();
        $logger->payload = 'logger';
        $logger->message = 'Pause Manager Console Finished on '. Carbon::now()->toDayDateTimeString();
        $logger->save();
    }
}
