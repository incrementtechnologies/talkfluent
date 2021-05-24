<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Payment;
class BillingManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:canceled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage canceled payments to take effectivity on the server.';

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
        dispatch(new Payment());
    }
}
