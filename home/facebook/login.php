<?php
require_once 'sdk5/src/Facebook/autoload.php';
session_start();
if(isset($_GET['state'])) {
    $_SESSION['FBRLH_state'] = $_GET['state'];
}

/*Step 1: Enter Credentials*/
$fb = new Facebook\Facebook([
  'app_id' => '570823863304300', // Replace {app-id} with your app id
  'app_secret' => 'e4ba8f2d796963a8b8228236e1fe97d3',
  'default_graph_version' => 'v2.2',
  ]);

/*Step 2 Create the url*/
if(empty($access_token)) {
    echo "<a href='{$fb->getRedirectLoginHelper()->getLoginUrl("https://talkfluentspanish.com/facebook/callback.php")}'>Login with Facebook </a>";
}
?>