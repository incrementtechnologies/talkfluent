<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EmailForgetPassword implements ShouldQueue
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
                    <h1>Request to Reset Password on TalkFluent</h1>
                </span>
                <span class=content>
                    <p>
                        Hello! You've requested to reset your password using this email address at ".$this->account['email'].".
                    </p>
                    <p>Tap the button to continue:</p>
                    <p>
                        <a class=link href=".$this->host['app'].'reset_password/'.$this->account['username'].'/'.$this->account['code'].">
                            <button class=btn>Continue</button>
                        </a>
                    </p>
                    <p>
                        <h3>Didn't request this email?</h3>
                        <p>
                        No Worries! Your address may have been entered by mistake. If you ignore or delete this email, nothing further will happen.
                        </p>
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
        $headers .= 'From: '.$this->host['app_title'].'<'.$this->host['email'].'>' . "\r\n";
        return $headers;

    }
    public function send(){
        $to = $this->account['email'];
        $message = $this->template();
        $headers = $this->header();
        $subject = 'Reset Password Request for '.$this->host['app_title'];
        mail($to,$subject,$message,$headers);
    }
}
