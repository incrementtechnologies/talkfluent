<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Billing as BillingJobs;
class Billing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo '[Billing Command] Start running ...';
        BillingsJob::dispatch();
        echo "\n[Billing Command] Completed";
    }
}
