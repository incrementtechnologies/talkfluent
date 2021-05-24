<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Receipt extends Mailable
{
    use Queueable, SerializesModels;
    public $account;
    public $host;
    public $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($account, $host, $details)
    {
        $this->account = $account;
        $this->host = $host;
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@talkfluentspanish.com')->view('mail.receipt');
    }
}
