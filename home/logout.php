<?php
  $host = $_SERVER['SERVER_NAME'];
  if($host == "localhost"){
    $host = 'http://'.$host.'/talkfluent/home';
  }else{
    if($host == "hablainglesfluido.com"){
      $host = 'https://'.$host;
    }else{
      $host = 'https://'.$host;
    }
  }
  session_start();
  $blogId = $_SESSION['blog_id'];
  session_unset(); 
  session_destroy();
  header('Location: '.$host.'/blog?id='.$blogId);
?>