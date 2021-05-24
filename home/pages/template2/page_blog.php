<?php
  require_once __DIR__.'/../../facebook/sdk5/src/Facebook/autoload.php';
  require_once __DIR__.'/../../google/gplus/vendor/autoload.php';
  session_start();
  



  // FACEBOOK
  $fb = new Facebook\Facebook([
    'app_id' => $fbKeys['app_id'],
    'app_secret' => $fbKeys['app_secret'],
    'default_graph_version' => 'v2.2',
  ]);
  $helper = $fb->getRedirectLoginHelper();
  $_SESSION['FBRLH_state']=$_GET['state'];
  $permissions = ['email'];
  $callback = $helper->getLoginUrl($host.'/facebook/callback.php', $permissions);





  // GOOGLE
  $google_id = $googleKeys['app_id'];
  $google_client_secret = $googleKeys['app_secret'];
  $google_redirect_uri = $host.'/google/callback.php';
  $client = new Google_Client();
  $client->setClientId($google_id);
  $client->setClientSecret($google_client_secret);
  $client->setRedirectUri($google_redirect_uri);
  $client->setApplicationName('Login to '.$host);
  $client->setScopes('email');
  $plus = new Google_Service_Plus($client);
  $google_login = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode($google_redirect_uri) . '&response_type=code&client_id=' . $google_id . '&access_type=online';



  function getBlogId(){
    // create blog parameter for title
    $uri = $_SERVER['REQUEST_URI'];
    $getData = explode('id=', $uri);
    return $getData[1];
  }




  function getURL(){
    $TALKFLUENTSPANISH = 'https://api.talkfluentspanish.com/api/public/blogs/retrieve_custom'; // talkfluentspanish
    $HABLAINGLESFLUIDO = 'https://api.hablainglesfluido.com/api/public/blogs/retrieve_custom'; // habla
    $DEV_BACKEND_URL = 'http://localhost/talkfluent/api/public/blogs/retrieve_custom';
    $host = $_SERVER['SERVER_NAME'];
    $api = null;
    if($host == "localhost"){
      $api = $TALKFLUENTSPANISH;
    }else if($host == 'talkfluentspanish.com'){
      $api = $TALKFLUENTSPANISH;
    }else{
      $api = $HABLAINGLESFLUIDO;
    }
    return $api;
  }




  function getBlogContent(){
    $parameter = array(
      'condition' => array(
        array(
        'value'   => getBlogId(),
        'clause'  => '=',
        'column'  => 'id'
      ))
    );
    $ch = curl_init();
    $url = getURL();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameter));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
  }



  $response = getBlogContent();
  $_SESSION['blog_id'] = getBlogId();
  $data = json_decode($response, true);
?>
<div class="page-holder">
  <div class="page-content-template2">
    <span class="header">
      <span class="title"><?php echo $data[0]['title'];?></span>
    </span>
    <div class="page-content-blog">
      <span class="blog-content-container">
      <?php
        $comments = $data[0]['comments'];
        echo "<span class=title><img src=".$api.$data[0]['featured_image']." width=100% ></span>";
        echo "<span class=date-posted> Posted on ".$data[0]['created_at']."</span>";
        $i = 0;
        foreach ($data as $key) {
          echo "<span class=content>";
          // echo "<p><b>".$data[$i]['title']."</b></p>";
          echo "<p class=text-gray>".$data[$i]['content']."</p>";
          echo "</span>";
          $i++;
        }
        $commentText = (sizeof($comments) > 1) ? ''.sizeof($comments) ." Comments" : ''.sizeof($comments) ." Comment";
        echo '</span>';
        echo "<div class=blog-comments>
                <span class=comment-header><span class=text>".$commentText."</span>";
        /*Step 2 Create the url*/
        if(!isset($_SESSION['access_token'])) {
          echo "<div class='dropdown pull-right'>
                  <button class='logout dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                    Login
                  </button>
                  <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                    <a class='dropdown-item' href=".htmlspecialchars($callback).">Facebook</a>
                    <a class='dropdown-item' href=".$google_login.">Google</a>
                  </div>
                </div>";
        }else{
          // Loggedin
          echo "<div class='dropdown pull-right'>
                  <button class='loggedin dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                    Hi ".$_SESSION['name']."
                  </button>
                  <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                    <a class='dropdown-item' href=".$host."/logout>Logout</a>
                  </div>
                </div>";
        }
        echo "</span>";
        // New Comment
        echo "<span class=comment-input>";
        echo "<textarea class=form-control id=comment-text placeholder='Join the discussion...'></textarea>";
        if(!isset($_SESSION['access_token'])) {
          echo "<span class='login-button'>
                <span class=text>LOGIN WITH</span>
                <span class=icons>
                  <a href=".htmlspecialchars($callback)."><i class='fab fa-facebook-square text-primary'></i></a>
                  <a href=".$google_login."><i class='fab fa-google-plus-square text-danger'></i></a>
                </span>
              </span>";
        }else{
          echo '<button class="btn btn-primary" onclick="submitComment(\''.getBlogId().'\',\''.$_SESSION['name'].'\',\''.$_SESSION['email'].'\',\''.$_SESSION['profile'].'\', \''.$_SESSION['using'].'\', \''.$_SESSION['userID'].'\')">Comment</button>';
        }
        echo "</span>";
        // Comments
        $cI = 0;
        foreach ($comments as $key) {
          // Comments
          echo "<span class=comment-content>
                  <span class=account-info>";
          if($comments[$cI]['profile_url'] == null){
            echo "<i class='fa fa-user-circle' style='font-size: 32px;'></i>";
          }else{
            echo "<img src=".$comments[$cI]['profile_url']." height=40px width=40px>";
          }
          echo "<label>".$comments[$cI]['name']."</label></span>";
          echo "<span class=text>".$comments[$cI]['comment']."</span>";
          echo "<span class=date>Posted on ".$comments[$cI]['created_at'];
          echo "<label onclick=toggleReply('reply-',"."'".$comments[$cI]['id']."')>Reply</label></span></span>";
          // Replies
          $replies = $comments[$cI]['replies'];
          if($replies !== null){
            $rI = 0;
            foreach ($replies as $keyR) {
              echo "<span class=blog-comment-replies>
                      <span class=account-info>";
              if($replies[$rI]['profile_url'] == null){
                echo "<i class='fa fa-user-circle' style='font-size: 32px;'></i>";
              }else{
                 echo "<img src=".$replies[$rI]['profile_url']." height=40px width=40px>";
              }
              echo "<label>".$replies[$rI]['name']."</label></span>";
              echo "<span class=text>".$replies[$rI]['reply']."</span>";
              echo "<span class=date>Posted on ".$replies[$rI]['created_at']."</span></span>";
              $rI++;
            }
            echo "<span class=reply-input id=reply-".$comments[$cI]['id'].">";
            echo "<textarea class=form-control autofocus id=reply-text-".$comments[$cI]['id']." placeholder='Join the discussion..'></textarea>";
            if(!isset($_SESSION['access_token'])) {
              echo "<span class='login-button'>
                    <span class=text>LOGIN WITH</span>
                    <span class=icons>
                      <a href=".$callback."><i class='fab fa-facebook-square text-primary'></i></a>
                      <a href=".$google_login."><i class='fab fa-google-plus-square text-danger'></i></a>
                    </span>
                  </span>";
            }else{
              echo '<button class="btn btn-primary" onclick="submitReply(\''.$comments[$cI]['id'].'\',\''.$_SESSION['name'].'\',\''.$_SESSION['email'].'\',\''.$_SESSION['profile'].'\', \''.$_SESSION['using'].'\', \''.$_SESSION['userID'].'\')">Reply</button>';
            }
            echo "</span>";
          }else{
            echo "<span class=reply-input id=reply-".$comments[$cI]['id'].">";
            echo "<textarea class=form-control autofocus id=reply-text-".$comments[$cI]['id']." placeholder='Join the discussion..'></textarea>";
           if(!isset($_SESSION['access_token'])) {
              echo "<span class='login-button'>
                    <span class=text>LOGIN WITH</span>
                    <span class=icons>
                      <a href=".$callback."><i class='fab fa-facebook-square text-primary'></i></a>
                      <a href=".$google_login."><i class='fab fa-google-plus-square text-danger'></i></a>
                    </span>
                  </span>";
            }else{
              echo '<button class="btn btn-primary" onclick="submitReply(\''.$comments[$cI]['id'].'\',\''.$_SESSION['name'].'\',\''.$_SESSION['email'].'\',\''.$_SESSION['profile'].'\', \''.$_SESSION['using'].'\', \''.$_SESSION['userID'].'\')">Reply</button>';
            }
            echo "</span>";
          }
          $cI++;
        }
      ?>
      </div>
    </div>
    <div class="sidebar-blog">
       <?php include 'page_sidebar.php';?>
    </div>
  </div>
</div>
<div class="customModal" id="loadingModalBlog">
  <span class="loading">
    <i class="fas fa-spinner fa-spin"></i>
  </span>
</div>