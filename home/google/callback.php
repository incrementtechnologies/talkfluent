<?php
// Callback
// GOOGLE

$host = $_SERVER['SERVER_NAME'];

// TAlkfluentSpanish
$googleKeys = array(
  'app_id'  => '49198764890-9kuviq148o4giuf6k65po7ahav6d6adh.apps.googleusercontent.com',
  'app_secret' => 'W3LPXVA0LU7J-jKkrW3eDYkg'
);

if($host == "localhost"){
  $host = 'http://'.$host.'/talkfluent/home';
}else{
  if($host == "hablainglesfluido.com"){
    $host = 'https://'.$host;

    $googleKeys = array(
      'app_id'  => '712664654523-n9pr9vqb8ui1d6hqubccq94lqiqfh2vu.apps.googleusercontent.com',
      'app_secret' => 'QCqtnbdLGwf1x-WjHPgMWdLw'
    );
  }else{
    $host = 'https://'.$host;

    $googleKeys = array(
      'app_id'  => '49198764890-9kuviq148o4giuf6k65po7ahav6d6adh.apps.googleusercontent.com',
      'app_secret' => 'W3LPXVA0LU7J-jKkrW3eDYkg'
    );
  }
}


require_once 'gplus/vendor/autoload.php';
session_start();
$google_id = $googleKeys['app_id'];
$google_client_secret = $googleKeys['app_secret'];
$google_redirect_uri = $host.'/google/callback.php';
$client = new Google_Client();
$client->setClientId($google_id);
$client->setClientSecret($google_client_secret);
$client->setRedirectUri($google_redirect_uri);
$client->setScopes('email');
$plus = new Google_Service_Plus($client);


if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
}
/* 
 * C. RETRIVE DATA
 * 
 * If access token if available in session 
 * load it to the client object and access the required profile data
 */
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
  $me = $plus->people->get('me');
  // Get User data
  $_SESSION['name'] = $me['displayName'];
  $_SESSION['email'] = $me['emails'][0]['value'];
  $_SESSION['profile'] = $me['image']['url'];
  $_SESSION['userID'] =$me['id'];
  $_SESSION['using'] = 'google';
} else {
  // get the login url   
  $authUrl = $client->createAuthUrl();
}

if(isset($_SESSION['blog_id'])){
  header('Location: '.$host.'/blog?id='.$_SESSION['blog_id']);
}else{
  header('Location: '.$host.'/blog?id=1');
}
?>