<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
class ChangePassword extends Mailable
{
    use Queueable, SerializesModels;
    public $account;
    public $host;
    public $date;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($account, $host)
    {
        $this->account = $account;
        $this->host = $host;
        $this->date = Carbon::now()->copy()->tz('Asia/Manila')->format('F j, Y');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@talkfluentspanish.com')->view('mail.changepassword');
    }
}
