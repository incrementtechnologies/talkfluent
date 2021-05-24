<?php

namespace App\Http\Controllers;
use Mail;
use Illuminate\Http\Request;
use App\Mail\Verification;
use App\Mail\ChangePassword;
use App\Mail\ForgetPassword;
use App\Mail\Receipt;
use App\ActiveCampaign;
use Carbon\Carbon;
class EmailController extends TalkController
{

  function __construct(){
    //
  }

  public function verification($accountId, $host){
    $account = $this->getAccount($accountId);
    if($account != null){
      Mail::to($account['email'])->send(new Verification($account, $host));
      return true;
    }
    return false;
  }

  public function changePassword($accountId, $host){
    $account = $this->getAccount($accountId);
    if($account != null){
      Mail::to($account['email'])->send(new ChangePassword($account, $host));
      return true;
    }
    return false;
  }


  public function forgetPassword($accountId, $host){
    $account = $this->getAccount($accountId);
    if($account != null){
      Mail::to($account['email'])->send(new ForgetPassword($account, $host));
      return true;
    }
    return false;
  }

  public function receipt($accountId, $host, $details){
    $account = $this->getAccount($accountId);
    if($account != null){
      Mail::to($account['email'])->send(new Receipt($account, $host, $details));
      return true;
    }
    return false;
  }

  public function testEmail(Request $request){
    $data = $request->all();
    $host = $data['host'];
    $account = $this->getAccount($data['account_id']);
    if($account != null){
      Mail::to($account['email'])->send(new Verification($account, $host));
      return response()->json(array(
        'data' => true,
        'timestamps' => Carbon::now()
      ));
    }
    return response()->json(array(
        'data' => false,
        'timestamps' => Carbon::now()
      ));;
  }


  public function testEmailReceipt(Request $request){
    $data = $request->all();
    $host = null;
    $account = $this->getAccount($data['account_id']);
    if($account != null){
      $details = array(
        'description' => 'Monthly Subscription',
        'unit_price' => 24.00,
        'qty' => 1,
        'amount' => 24.00,
        'subtotal' => 24.00,
        'tax' => 0,
        'discount' => 0,
        'total' => 24.00
      );
      Mail::to($account['email'])->send(new Receipt($account, $host, $details));
      return response()->json(array(
        'data' => true,
        'timestamps' => Carbon::now()
      ));
    }
    return response()->json(array(
        'data' => false,
        'timestamps' => Carbon::now()
      ));;
  }

  public function testActiveCampaign(){
    $array = array(
      'email'       => 'kennettecanales@gmail.com',
      'first_name'  => 'Kennette',
      'last_name'   => 'Canales'
    );

    $test = new ActiveCampaign($array);
    echo json_encode($test);
  }
}
