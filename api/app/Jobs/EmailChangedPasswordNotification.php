<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Carbon\Carbon;

class EmailChangedPasswordNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    
    protected $account;
    protected $host;

    protected $header = "
        <html>
        <head>
        <title>Email Verificaiton</title>
        
        <style>
            .email-holder{
                width: 100%;
                float: left;
                min-height: 200px;
                overflow-y: hidden;
            }

            .logo{
                width: 100%;
                float: left;
                height: 75px;
            }

            .title{
                width: 100%;
                float: left;
            }

            .content{
                width: 100%; 
                float: left;
            }

            .link{
                text-decoration: none;
            }

            .btn{
                height: 50px;
                width: 300px;
                text-align: center;
                border-radius: 5px;
                background: #00bff3;
                color: #fff;
                border: 1px;
            }

            .btn:hover{
                cursor: pointer;
            }

        </style>
        </head>
        <body>
    ";

    public function __construct($account, $host)
    {
        $this->account = $account;
        $this->host = $host;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->send();
    }
    public function template(){
        $message = $this->header."
            <span class=email-holder>
                <span class=logo>
                    <img src= ".$this->host['api']."/storage/logo/talk.png height=60px width=auto/>
                </span>
                <span class=title>
                    <h1>Password was successfully changed!</h1>
                </span>
                <span class=content>
                    <p>
                        Hello ".$this->account['username']."!
                    </p>
                    <p>
                        You've have successfully changed your password.
                    </p>
                    <p>
                        Your password was changed on ".Carbon::now()." using ".$this->host['browser']."
                    </p>
                </span>
            </span>
            </body>
            </html>
        ";
        return $message;
    }

    public function header(){
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: '.$this->host['app_title'].'<'.$this->host['host_email'].'>' . "\r\n";
        return $headers;

    }
    public function send(){
        $to = $this->account['email'];
        $message = $this->template();
        $headers = $this->header();
        $subject = 'Password Changed for '.$this->host['app_title'];
        mail($to,$subject,$message,$headers);
    }
}
