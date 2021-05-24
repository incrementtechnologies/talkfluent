<!DOCTYPE html>
<html style="height:100%">
  <head>
    <?php 
      $host = $_SERVER['SERVER_NAME'];
      $hostSocial = null;
      $app = null;
      $headerTitle = null;
      $api = null;
      $logo = null;

      // Default Talkfluent Spanish
      $fbKeys = array(
        'app_id' => '570823863304300',
        'app_secret' => 'e4ba8f2d796963a8b8228236e1fe97d3'
      );

      $googleKeys = array(
        'app_id'  => '49198764890-9kuviq148o4giuf6k65po7ahav6d6adh.apps.googleusercontent.com',
        'app_secret' => 'W3LPXVA0LU7J-jKkrW3eDYkg'
      );
      if($host == "localhost"){
        $hostSocial = 'http://'.$host;
        $host = 'http://'.$host.'/talkfluent/home';
        $api = 'http://api.talkfluentspanish.com/api/public';
        $app = 'http://localhost:8080/#';
        $logo = $host.'/img/habla.png';
        $headerTitle = 'DEV PLATFORM';
      }else{
        if($host == "hablainglesfluido.com"){
          $hostSocial = 'https://'.$host;
          $host = 'https://'.$host;
          $logo = $host.'/img/habla.png';
          $app = 'https://app.hablainglesfluido.com/#';
          $api = 'https://api.hablainglesfluido.com/api/public';
          $headerTitle = 'Habla Ingles Fluido';

          $fbKeys = array(
            'app_id' => '2034065683574228',
            'app_secret' => '59107f561412441f7e20698463f839df'
          );

          $googleKeys = array(
            'app_id'  => '712664654523-n9pr9vqb8ui1d6hqubccq94lqiqfh2vu.apps.googleusercontent.com',
            'app_secret' => 'QCqtnbdLGwf1x-WjHPgMWdLw'
          );
        }else if($host == "talkfluentspanish.com" || $host == 'home.talkfluentspanish.com'){
          $hostSocial = 'http://'.$host;
          $host = 'http://'.$host;
          $logo = $host.'/img/talk.png';
          $app = 'https://app.talkfluentspanish.com/#';
          $api = 'https://api.talkfluentspanish.com/api/public';
          $headerTitle = 'Talk Fluent Spanish';

          $fbKeys = array(
            'app_id' => '570823863304300',
            'app_secret' => 'e4ba8f2d796963a8b8228236e1fe97d3'
          );

          $googleKeys = array(
            'app_id'  => '49198764890-9kuviq148o4giuf6k65po7ahav6d6adh.apps.googleusercontent.com',
            'app_secret' => 'W3LPXVA0LU7J-jKkrW3eDYkg'
          );
        }else{
          $host = 'http://192.168.1.20/talkfluent/home';
          $logo = $host.'/img/habla.png';
          $headerTitle = 'DEV PLATFORM';
        }
      }
    ?>
    <title><?php echo $headerTitle;?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name=”format-detection” content=”telephone=no”>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo $host.'/css/design.css'; ?>">
    <link rel="stylesheet" href="<?php echo $host.'/css/header.css'; ?>">
    <link rel="stylesheet" href="<?php echo $host.'/css/homepage.css'; ?>">
    <link rel="stylesheet" href="<?php echo $host.'/css/step.css'; ?>">
    <link rel="stylesheet" href="<?php echo $host.'/css/page.css'; ?>">
    <link rel="stylesheet" href="<?php echo $host.'/css/faq.css'; ?>">
    <link rel="stylesheet" href="<?php echo $host.'/css/contact.css'; ?>">
    <link rel="stylesheet" href="<?php echo $host.'/css/blog_post.css'; ?>">
    <link rel="stylesheet" href="<?php echo $host.'/css/blog.css'; ?>">
    <link rel="stylesheet" href="<?php echo $host.'/css/sidebar_blog.css'; ?>">
    <link rel="stylesheet" href="<?php echo $host.'/css/pricing.css'; ?>">
    <link rel="stylesheet" href="<?php echo $host.'/css/footer.css'; ?>">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.9/css/all.css" integrity="sha384-5SOiIsAziJl6AWe0HWRKTXlfcSHKmYV4RBF18PPJ173Kzn7jzMyFuTtk8JA7QQG1" crossorigin="anonymous">
    <script src="//fast.wistia.com/embed/medias/j38ihh83m5.jsonp" async></script>
    <script src="//fast.wistia.com/assets/external/E-v1.js" async></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type="module"></script>
    <script src="<?php echo $host.'/js/session.js'; ?>" async></script>
    <script src="<?php echo $host.'/js/login.js'; ?>" async></script>
    <script src="<?php echo $host.'/js/action.js'; ?>" async></script>
    <script src="<?php echo $host.'/js/faq.js'; ?>" async></script>
    <script src="<?php echo $host.'/js/contact.js'; ?>" async></script>
    <script src="<?php echo $host.'/js/blog.js'; ?>" async></script>
    <script src="<?php echo $host.'/js/subscription.js'; ?>" async></script>
    <script>
      $(document).ready(function(){
        $(window).scroll(function() {
          if ($(this).scrollTop() >= 50) {
            $('#return-to-top').fadeIn(200)
          }else{
            $('#return-to-top').fadeOut(200)
          }
        });
        $('#return-to-top').click(function() {
          $('body,html').animate({
            scrollTop : 0
          }, 500)
        })
      })
    </script>
  </head>
  <body>
    <div class="header">
      <div class="header-section">
        <span class="logo">
          <a class="navbar-brand" href="<?php echo $host;?>">
            <img src="<?php echo $logo;?>" style="margin-bottom: 5px;">
          </a>
        </span>
        <span class="menu">
          <span class="top-menu">
              <span>1800</span> <span>000</span> <span>0000</span>
          </span>
          <span class="navbar-menu-toggler-md">
            <i class="fa fa-bars" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" onclick="void(0)"></i>
          </span>
          <ul class="header-primary-menu">
            <li class="nav-item" >
              <a class="btn btn-white btn-header" href="<?php echo $app;?>" style="margin-left: .5rem; margin-right: 0px !important;">Login</a>
            </li>
            <li class="nav-item" >
              <a class="btn btn-green btn-header" href="<?php echo $host.'/pricing';?>" style="margin-right: .5rem; margin-left: .5rem;">Get Started</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $host.'/contact';?>">Contact Us</a></li>
            <li class="nav-item"><a class="nav-link" class="nav-link" href="<?php echo $host.'/faq';?>">FAQ</a></li><!-- 
            <li class="nav-item"><a class="nav-link" href="<?php // echo $host.'/blog_posts';?>">Blog</a></li> -->
            <li class="nav-item"><a class="nav-link" href="<?php echo $host.'/pricing';?>">Pricing</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $host.'/about';?>">About Us</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $host;?>">Home</a></li>
          </ul>
        </span>
      </div>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a class="nav-link" href="<?php echo $host.'/pricing';?>">Get Started</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $app;?>">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $host;?>">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $host.'/about';?>">About Us</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $host.'/pricing';?>">Pricing</a></li><!-- 
          <li class="nav-item"><a class="nav-link" href="<?php // echo $host.'/newsletter';?>">Newsletter</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php // echo $host.'/blog_posts';?>">Blog</a></li> -->
          <li class="nav-item"><a class="nav-link" class="nav-link" href="<?php echo $host.'/faq';?>">FAQ</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $host.'/contact';?>">Contact Us</a></li>
<!--           <li style="margin-bottom: 20px !important; margin-top: 5px;" id="lead-buttons">
            <a class="btn btn-green" href="<?php // echo $app.'/signup';?>">Get Started</a>
            <a class="btn btn-white" href="<?php // echo $app;?>">Login</a>
          </li> -->
        </ul>
      </div>
    </div>