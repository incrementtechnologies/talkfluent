<?php
$host = $_SERVER['SERVER_NAME'];

// TalkfluentSpanish
$fbKeys = array(
  'app_id' => '570823863304300',
  'app_secret' => 'e4ba8f2d796963a8b8228236e1fe97d3'
);
if($host == "localhost"){
  $host = 'http://'.$host.'/talkfluent/home';
}else{
  if($host == "hablainglesfluido.com"){
    $host = 'https://'.$host;
    $fbKeys = array(
      'app_id' => '2034065683574228',
      'app_secret' => '59107f561412441f7e20698463f839df'
    );
  }else{
    $host = 'https://'.$host;
    $fbKeys = array(
      'app_id' => '570823863304300',
      'app_secret' => 'e4ba8f2d796963a8b8228236e1fe97d3'
    );
  }
}
require_once 'sdk5/src/Facebook/autoload.php';
session_start();
$fb = new Facebook\Facebook([
  'app_id' => $fbKeys['app_id'],
  'app_secret' => $fbKeys['app_secret'],
  'default_graph_version' => 'v2.2',
]);
/*Step 3 : Get Access Token*/
$helper = $fb->getRedirectLoginHelper();
$_SESSION['FBRLH_state']=$_GET['state'];
$access_token = $helper->getAccessToken();
/*Step 4: Get the graph user*/
if(isset($access_token)) {
    try {
        $response = $fb->get('/me',$access_token);
        $userInfo = $response->getGraphUser();
        $_SESSION['name'] = $userInfo->getName();
        $_SESSION['email'] = $userInfo->getEmail();
        $_SESSION['userID'] = $userInfo->getId();
        $_SESSION['access_token'] = (string) $accessToken;
        $_SESSION['profile'] = 'https://graph.facebook.com/'.$userInfo->getId().'/picture?width=40&height=40';
        $_SESSION['using'] = 'facebook';
    } catch (\Facebook\Exceptions\FacebookResponseException $e) {
        echo  'Graph returned an error: ' . $e->getMessage();
    } catch (\Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
    }
}else{
  //
}
if(isset($_SESSION['blog_id'])){
  header('Location: '.$host.'/blog?id='.$_SESSION['blog_id']);
}else{
  header('Location: '.$host.'/blog?id=1');
}
?>
