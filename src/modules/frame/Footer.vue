<template>
  <footer>
    <div class="footer">
      <!-- <ul class="footer-widget contact-us"> -->
        <!-- <li class="title"><b>Contacts</b></li> -->
        <!-- <li class="link"><i class="fa fa-phone"></i><span>1800</span> <span>000</span> <span>0000</span></li> -->
        <!-- <li class="link"><i class="fa fa-envelope"></i> {{'support@' + config.WEBHOST}}</li> -->
      <!-- </ul> -->
      <ul class="footer-widget">
        <li class="title"><b>Company</b></li>
        <li class="link"><a v-bind:href="host" target="_BLANK">Home</a></li>
        <li class="link"><a v-bind:href="host + '/about'" target="_BLANK" >About Us</a>
        <li class="link"><a v-bind:href="host + '/pricing'" target="_BLANK">Pricing</a></li><!-- 
        <li class="link"><a v-bind:href="host + '/newsletter'" target="_BLANK">Newsletter</a></li>
        <li class="link"><a v-bind:href="host + '/blog_posts'" target="_BLANK">Blog</a></li> -->
        <li class="link"><a v-bind:href="host + '/faq'" target="_BLANK">FAQ</a></li>
        <li class="link"><a v-bind:href="host + '/contact'" target="_BLANK">Contact Us</a></li>
      </ul>
      <ul class="footer-widget">
        <li class="title"><b>Privacy and Terms</b></li>
        <li class="link"><a v-bind:href="host + '/terms-and-conditions'" target="_BLANK">Terms & Conditions</a></li>
        <li class="link"><a v-bind:href="host + '/privacy-policy'" target="_BLANK">Privacy Policy</a></li>
        <!-- <li class="link"><a v-bind:href="host + '/disclaimer'" target="_BLANK">Disclaimer</a></li> -->
        <!-- <li class="link"><a v-bind:href="host + '/copyright'" target="_BLANK">Copyright</a></li> -->
      </ul>
      <ul class="footer-widget">
        <!-- <li class="title"><b>Resources</b></li> -->
        <!-- <li class="link"><a v-bind:href="host + '/sitemap'" target="_BLANK">Sitemap</a></li> -->
      </ul>
      <ul class="footer-widget">
      </ul>
      <span class="footer-widget community">
        <span class="title"><b>Community</b></span>
        <span class="link">
          <a target="_BLANK" class="text-gray" v-bind:href="host"><i class="fab fa-facebook"></i></a>
          <a target="_BLANK" class="text-gray" v-bind:href="host"><i class="fab fa-pinterest"></i></a>
          <a target="_BLANK" class="text-gray" v-bind:href="host"><i class="fab fa-twitter"></i></a>
          <a target="_BLANK" class="text-gray" v-bind:href="host"><i class="fab fa-instagram"></i></a>
          <a target="_BLANK" class="text-gray" v-bind:href="host"><i class="fab fa-tumblr"></i></a>
        </span>
      </span>
      
      <span class="copyright">
        <label>Copyright @{{config.WEBSITE_TITLE + ' ' + config.YEAR}}. All rights reserved.</label>
      </span>
    </div>
  </footer>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
export default {
  mounted(){
    this.host = (this.config.SITE_FLAG === 2) ? 'https://hablainglesfluido.com' : 'https://talkfluentspanish.com'
  },
  data(){
    return{
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      account: [],
      config: CONFIG,
      host: null
    }
  }
}
</script>
<style>
footer {
  width:100%;
  min-height:5vh;   /* Height of the footer */
  float: left;
  overflow-y: hidden;
  background: #e9e9e9;
}
.footer{
  width: 70%;
  float: left;
  min-height: 50px;
  margin-left: 15%; 
  margin-right: 15%;
  overflow-y: hidden;
}

.footer-widget{
  width: 20%;
  float: left;
  margin-top: 50px;
  list-style: none;
  padding: 0px;
  margin-bottom: 0px;
}


.footer-widget li{
  list-style: none;
  padding: 5px 10px 5px 10px;
  float: left;
  width: 100%;
  font-weight: 500; 
  color: #555555 !important;
}

.footer-widget, .footer-widget li a{
  color: #555555 !important;
}
.footer-widget a{
  font-size: 15px;
  font-weight: 600;
  

}
.footer-widget .title{
  color: #555555;
  font-size: 18px;
}

.footer-widget li a:hover{
  cursor: pointer;
  text-decoration: underline;
}
footer-widget .title:hover{
  cursor: default;
}

.community .title{
  width: 100%;
  float: left;
  height: 40px;
  text-align: left;
  padding-top: 5px;
} 
.community .links{
  width: 100%;
  float: left;
}
.community .link i{
  font-size: 24px;
  padding: 0 10px 0 10px;
  float: left;
  width: 20%;
}
.community .link i:hover{
  color: green;
  cursor: pointer;
}
.text-gray{
  color: #555555;
}

.contact-us li i{
  padding-right: 10px;
}

.contact-us li .fa-phone{
  padding-right: 9px;
}

.contact-us li .fa-mobile-alt{
  padding-right: 12px;
}

.copyright{
  width: 100%;
  float: left;
  padding-top: 5px;
  padding-bottom: 5px;
  font-size: 15px;
  font-weight: bold;
  text-align: center;
  margin-top: 45px;
  color: #555555 !important;
}
@media screen and (max-width: 992px){
  .footer-widget{
    width: 100%;
    text-align: center;
    margin-right: 0%;
    padding: 0px;
  }

  .community .title{
    text-align: center;
  }

  .community i{
    width: 20%;
  }
}
</style>
