<span class="item bordered">
  <h3>Join Us</h3>
  <p id="subscribes_error">
    
  </p>
  <p>
    subscribes to get free conversation optimization tips and resources.
  </p>
  <input type="text" class="form-control" placeholder="Your Complete Name" id="subscribes_name" />
  <input type="text" class="form-control" placeholder="Your Email Address" id="subscribes_email"/>
  <button class="btn btn-green" onclick="subscribes()">SUBSCRIBE NOW!</button>
</span>
<span class="item banner">
  <h2>Banner Here</h2>
</span>
<span class="item"><h3>Trending Posts</h3></span>
<?php
  function getURLTrending(){
    $TALKFLUENTSPANISH = 'https://api.talkfluentspanish.com/api/public/blog_comments/retrieve_by_counts'; // talkfluentspanish
    $HABLAINGLESFLUIDO = 'https://api.hablainglesfluido.com/api/public/blog_comments/retrieve_by_counts'; // habla
    $DEV_BACKEND_URL = 'http://localhost/talkfluent/api/public/blog_comments/retrieve_by_counts';
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




  function getTrendingBlog(){
    $parameter = null;
    $ch = curl_init();
    $url = getURLTrending();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameter));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
  }

  $responseTrending = getTrendingBlog();
  $trendingResult = json_decode($responseTrending, true);
?>

<?php
  $trending = $trendingResult['data'];

  $limitTrending = 5;

  if(sizeof($trending) < 5){
    $i = 0;
    foreach ($trending as $key) {
      $commentText = (intval($trending[$i]['total']) > 1) ? ' comments' : ' comment';
      if($i == (sizeof($trending) - 1)){
        echo "<span class='trending-item trending-bottom'>";
        echo "<span class=trending-title onclick=redirect('".$host.'/blog?id='.$trending[$i]['blog_id']."')>".$trending[$i]['blog']['title']."</span>";
        echo "<span class=comment>".$trending[$i]['total'].$commentText."</span>";
        echo "</span>";
      }else{
        echo "<span class=trending-item>";
        echo "<span class=trending-title onclick=redirect('".$host.'/blog?id='.$trending[$i]['blog_id']."')>".$trending[$i]['blog']['title']."</span>";
        echo "<span class=comment>".$trending[$i]['total'].$commentText."</span>";
        echo "</span>";
      }
      $i++;
    }

  }else{
    for ($i=0; $i < $limitTrending; $i++) {
      $commentText = (intval($trending[$i]['total']) > 1) ? ' comments' : ' comment';
      if($i == ($limitTrending - 1)){
        echo "<span class='trending-item trending-bottom'>";
        echo "<span class=trending-title onclick=redirect('".$host.'/blog?id='.$trending[$i]['blog_id']."')>".$trending[$i]['blog']['title']."</span>";
        echo "<span class=comment>".$trending[$i]['total'].$commentText."</span>";
        echo "</span>";
      }else{
        echo "<span class=trending-item>";
        echo "<span class=trending-title onclick=redirect('".$host.'/blog?id='.$trending[$i]['blog_id']."')>".$trending[$i]['blog']['title']."</span>";
        echo "<span class=comment>".$trending[$i]['total'].$commentText."</span>";
        echo "</span>";
      }
    }
  }
?>