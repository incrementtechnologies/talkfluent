<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
class Verification extends Mailable
{
    use Queueable, SerializesModels;
    public $account;
    public $host;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($account, $host)
    {
        $this->account = $account;
        $this->host = $host;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@talkfluentspanish.com')->view('mail.verification');
    }
}
