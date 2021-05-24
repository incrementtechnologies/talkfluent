<?php

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
          'column'  => 'status',
          'value'   => 'PUBLISHED',
          'clause'  => '='
        )
      ),
      'sort' => array(
        'created_at' => 'DESC'
      )
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
?>
<div class="page-holder">
  <div class="page-content-template2">
    <span class="header">
      <span class="title">Blog Posts</span>
    </span>
    <div class="page-content-blog">
      <span class="tags-header"><h3>Trending</h3></span>
      <?php
        $response = json_decode($response, true);
        if(sizeof($response) > 0){
          $i = 0;
          foreach($response as $key) {
            // echo "<a href=".$host.'/blog?id='.$response[$i]['id'].">";
            echo "<span class=post-item>";
            echo "<span class=featured-img>";
            echo "<img src=".$api.$response[$i]['featured_image']." />";
            echo "</span>";
            echo "<span class=blog-title-details>";
              echo "<span class=post-title onclick=redirect('".$host.'/blog?id='.$response[$i]['id']."')><h4>".$response[$i]['title']."</h4></span>";
              echo "<span class=post-date>Posted on ".$response[$i]['created_at']."</span>";
              // echo "<span class=post-action><label style='float:left; width: 30%;' onclick=redirect('".$host.'/blog?id='.$response[$i]['id']."')>9 comments</label><label style='float:left; width: 70%;'>share</label></span>";
              echo "</span>";
            echo "</span>";
            //echo "</a>";
            $i++;
          }
        }
      ?>
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
