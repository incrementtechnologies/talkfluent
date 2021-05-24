    <?php 
      include 'login.php';
    ?>
    <div class="footer">
      <div class="footer-widget-holder">
        <ul class="widget-item contact-us">
          <?php 
            $host = $_SERVER['SERVER_NAME'];
            $contactHost = $host;
            $app = null;
            if($host === "localhost"){
              $app = 'http://localhost:8080';
              $host = 'http://'.$host.'/talkfluent/home';
            }else{
              $app = 'https://app.'.$host;
              $host = 'https://'.$host;
            }
          ?>
          <li class="title"><b>Contact</b></li>
          <li class="link"><i class="fa fa-phone"></i>  <span>1800</span> <span>000</span> <span>0000</span></li>
        </ul>
        <ul class="widget-item">
          <li class="title"><b>Company</b></li>
          <li class="link"><a href="<?php echo $host;?>">Home</a></li>
          <li class="link"><a href="<?php echo $host.'/about';?>">About Us</a></li>
          <li class="link"><a href="<?php echo $host.'/pricing';?>">Pricing</a></li>
<!--           <li class="link"><a href="<?php echo $host.'/newsletter';?>">Newsletter</a></li>
          <li class="link"><a href="<?php echo $host.'/blog_posts';?>">Blog</a></li> -->
          <li class="link"><a href="<?php echo $host.'/faq';?>">FAQ</a></li>
          <li class="link"><a href="<?php echo $host.'/contact';?>">Contact Us</a></li>
        </ul>
        <ul class="widget-item">
          <li class="title"><b>Privacy and Terms</b></li>
          <li class="link"><a href="<?php echo $host.'/terms_and_conditions';?>">Terms & Conditions</a></li>
          <li class="link"><a href="<?php echo $host.'/privacy_policy';?>">Privacy Policy</a></li>
          <li class="link"><a href="<?php echo $host.'/disclaimer';?>">Disclaimer</a></li>
          <li class="link"><a href="<?php echo $host.'/copyright';?>">Copyright</a></li>
        </ul>
        <ul class="widget-item">
          <li class="title"><b>Resources</b></li>
          <li class="link"><a href="<?php echo $host.'/sitemap';?>">Sitemap</a></li>
        </ul>
        <ul class="widget-item community">
          <li class="title"><b>Community</b></li>
          <li class="link">
            <a class="text-gray" href="<?php echo $host;?>"><i class="fab fa-facebook"></i></a>
            <a class="text-gray" href="<?php echo $host;?>"><i class="fab fa-pinterest"></i></a>
            <a class="text-gray" href="<?php echo $host;?>"><i class="fab fa-twitter"></i></a>
            <a class="text-gray" href="<?php echo $host;?>"><i class="fab fa-instagram"></i></a>
            <a class="text-gray" href="<?php echo $host;?>"><i class="fab fa-tumblr"></i></a>
          </li>
        </ul>
      </div>
      <span class="copyright">
        <label>Copyright @<?php echo $headerTitle;?> 2018. All rights reserved.</label>
      </span>
    </div>
  </body>
</html>
