<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Trial;
class TrialAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:trial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executes Trial Accounts and deactivate once finished';

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
        dispatch(new Trial());
    }
}
