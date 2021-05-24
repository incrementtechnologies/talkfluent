 <template>
  <div class="header-container-loggedin">
    <div class="header-holder-loggedin">
    <div class="logo-loggedin">
      <a class="navbar-brand-loggedin" v-on:click="redirect('/dashboard')">
        <img src="../../assets/img/logo_talk.png"  v-if="config.SITE_FLAG === 0">
        <img src="../../assets/img/logo_talk.png"  v-if="config.SITE_FLAG === 1">
        <img src="../../assets/img/logo_habla.png" v-if="config.SITE_FLAG === 2">
      </a>
    </div>
    <div class="menu-loggedin">
     <!--  <span class="header-top-menu-loggedin">
        <span>1800</span> <span>000</span> <span>0000</span>
        <span></span>
      </span> -->
      <span class="header-primary-menu-loggedin">
        <i class="fa fa-bars navbar-menu-toggler-md-loggedin" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"></i>
        <ul class="web-menu-loggedin">
          <li class="settings-dropdown-loggedin" data-toggle="dropdown" id="settings" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" v-on:click="menuToggle()"></i>
            <span class="dropdown-menu dropdown-menu-right" aria-labelledby="settings">
              <span class="dropdown-item-profile">
                <span class="account-picture text-center" v-if="user.profile === null">
                  <i class="fa fa-user-circle-o"></i>
                </span>
                <span class="account-picture" v-else>
                  <img :src="config.BACKEND_URL + user.profile.url">
                </span>
                <span class="account-info text-center">{{user.username}}</span>
              </span>
              <span class="dropdown-item" v-on:click="redirect('/category_lesson')" v-if="user.type === 'ADMIN'">Category Lesson</span>
              <span class="dropdown-item" v-on:click="redirect('/subcategory_lesson')" v-if="user.type === 'ADMIN'">Sub Category Lesson</span>
              <span class="dropdown-item" v-on:click="redirect('/lesson_management')" v-if="user.type === 'ADMIN'">Lesson Management</span>
              <span class="dropdown-item" v-on:click="redirect('/audio_files')" v-if="user.type === 'ADMIN'">Audio File Management</span>
              <span class="dropdown-item" v-on:click="redirect('/content_management')" v-if="user.type === 'ADMIN'">Content Management</span>
              <span class="dropdown-item" v-on:click="redirect('/word_popup')" v-if="user.type === 'ADMIN'">Word Popup</span>
              <span class="dropdown-item" v-on:click="redirect('/subscription')" v-if="user.type === 'ADMIN'">Subscriptions</span>
              <span class="dropdown-item" v-on:click="redirect('/sentence_popup')" v-if="user.type === 'ADMIN'">Sentence Popup</span>
              <span class="dropdown-item" v-on:click="redirect('/word_audio')" v-if="user.type === 'ADMIN'">Word Management</span>
              <span class="dropdown-item" v-on:click="redirect('/ipas')" v-if="user.type === 'ADMIN'">IPAs</span>
              <span class="dropdown-item" v-on:click="redirect('/ipa_classifications')" v-if="user.type === 'ADMIN'">IPA Classifications</span>
              <span class="dropdown-item" v-on:click="redirect('/word_images')" v-if="user.type === 'ADMIN'">Word Images</span>
              <span class="dropdown-item" v-on:click="redirect('/blog')" v-if="user.type === 'ADMIN'">Blog Management</span>
              <span class="dropdown-item" v-on:click="redirect('/inquiries')" v-if="user.type === 'ADMIN'">Inquiries</span>
              <span class="dropdown-item" v-on:click="redirect('/images')" v-if="user.type === 'ADMIN'">Images</span>
              <span class="dropdown-item" v-on:click="redirect('/dashboard')">My Dashboard</span>
              <span class="dropdown-item" v-on:click="redirect('/my_plan')">My Plan</span>
              <span class="dropdown-item" v-on:click="redirect('/logger')" v-if="user.type === 'ADMIN'">Logger</span>
              <span class="dropdown-item" v-on:click="redirect('/feedback')" v-if="user.type === 'ADMIN'">Feedback</span>
              <span class="dropdown-item" v-on:click="redirect('/my_profile')">My Profile</span>
              <!-- <span class="dropdown-item" v-on:click="redirect('/paypal')" v-if="user.type === 'ADMIN'">Paypal Manager</span> -->
              <span class="dropdown-item" v-on:click="logOut()">Logout</span>
            </span>
          </li>
          <a v-bind:href="config.WEBSITE + '/contact'" target="_BLANK" class="desktop-loggedin"><li class="item-loggedin">Contact Us</li></a>
          <a v-bind:href="config.WEBSITE + '/faq'" target="_BLANK" class="desktop-loggedin"><li class="item-loggedin">FAQ</li></a>
          <a v-bind:href="config.WEBSITE + '/pricing'" target="_BLANK" class="desktop-loggedin"><li class="item-loggedin">Pricing</li></a>
          <a v-bind:href="config.WEBSITE + '/about'" target="_BLANK" class="desktop-loggedin"><li class="item-loggedin">About Us</li></a>
          <a v-bind:href="config.WEBSITE" target="_BLANK" class="desktop-loggedin"><li class="item-loggedin">Home</li></a>
        </ul>
        </span>
      </div>
    </div>
    <div class="collapse navbar-collapse navbar-menu-loggedin mobile-toggle-loggedin" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <a v-bind:href="config.WEBSITE" target="_BLANK" class="nav-item" v-on:click="menuToggle()">
          <b><li>Home</li></b>
        </a>
        <a v-bind:href="config.WEBSITE + '/about'" target="_BLANK" class="nav-item" v-on:click="menuToggle()">
          <li>About Us</li>
        </a>
        <a v-bind:href="config.WEBSITE + '/pricing'" target="_BLANK" class="nav-item" v-on:click="menuToggle()">
          <li>Pricing</li>
        </a>
        <a v-bind:href="config.WEBSITE + '/faq'" target="_BLANK" class="nav-item" v-on:click="menuToggle()">
          <li>FAQ</li>
        </a>
        <a v-bind:href="config.WEBSITE + '/contact'" target="_BLANK" class="nav-item" v-on:click="menuToggle()">
          <li>Contact Us</li>
        </a>
      </ul>
    </div>
  </div>
</template>
<style scoped>
.header-container-loggedin{
  background:#00bff3;
  width: 100%;
  min-height: 70px;
  overflow-y: hidden;
  float: left;
}
.header-holder-loggedin{
  width: 80%;
  min-height: 70px;
  overflow-y: hidden;
  float: left;
  margin: 15px 10% 12px 10%;
}
.logo-loggedin{
  float: left;
  height: 50px;
  font-size: 24px;
  width: 30%;
  background: #00bff3;
  text-align: left;
}
.menu-loggedin{
  background: #00bff3;
  min-height: 50px;
  float: left;
  width: 70%;
  overflow-y: hidden;
}
.menu-loggedin .header-top-menu-loggedin{
  float: left;
  width: 100%;
  text-align: right;
  color: #fff;
}
.menu-loggedin .header-primary-menu-loggedin{
  min-height: 50px;
  float: left;
  width: 100%;
  overflow-y: hidden;
  margin-top: 10px !important;
}

/*-- navbar --*/
.logo-loggedin .navbar-brand-loggedin img{
  color: #fff;
  height: 60px;
  margin-top: 7px;
}
.navbar-menu-loggedin{
  width: 100%;
  height: auto;
  z-index: 100000 !important;
  color: #00bff3;
  background: #fff;
  position: absolute;
  margin-top: 85px;
}
.navbar-menu-loggedin ul{
  width: 100%;
  float:left;
  color: #00bff3;
  background: #fff;
}
.navbar-menu-loggedin ul .nav-item{
  width: 100%;
  float: left;
  color: #00bff3;
  background: #fff;
  text-align: center;
}
.navbar-menu-loggedin ul .nav-item li{
  padding-top: 15px;
}

#messagesHeader{
  margin-left: 70%;
}
.nav-item-loggedin{
  width: 5%;
  height: 50px;
  text-align: center;
  float: right;
  color: #fff;
  display: inline;
}

.web-menu-loggedin{
  list-style: none;
  width: 100%;
  float: right !important;
  padding: 0px !important;
  min-height: 50px;
  overflow-y: hidden;
  margin-bottom: 0px;
  font-weight: bold;
}
.web-menu-loggedin .item-loggedin{
  padding-left: 15px;
  padding-right: 15px;
  padding-top: 15px;
  float:right;
  color:#fff;
  font-size: 16px;
}

.web-menu-loggedin li:hover{
  text-decoration: underline;
}

.nav-item-loggedin span i{
  padding: 12px 0 15px 0;
  font-size: 24px;
}

.desktop-loggedin{
  display: block;
}
.mobile-loggedin{
  display: none;
}

.nav-item-loggedin .label{
  z-index: 1000;
  background: #ff0000;
  padding: 5px;
  margin: -10px 0 0 -10px;
  border-radius: 2px;
  border-color: solid 1px #ff0000;
}

.nav-item-loggedin:hover{
  background: #1c73eb;
  cursor: pointer;
}

.settings-dropdown-loggedin{
  width: 40px;
  min-height: 10px;
  overflow-y: hidden;
  float: right;
  border: solid 1px #fff;
  text-align: center;
  color: #fff;
  margin-left: 15px;
  margin-top: 5px;

}

.settings-dropdown-loggedin .fa-cog{
  padding-top: 10px;
  padding-bottom: 10px;
  font-size: 24px;
}

.settings-dropdown-loggedin:hover{
  cursor: pointer;
  background: #1c73eb !important;
  color: #fff !important;
}
.dropdown-menu{
  width: 250px;
  min-height: 250px;
  border-radius: 0px !important;
  margin-top: 45px !important;
  margin-right: -6px !important;
  padding-bottom: 0px !important;
  padding-top: 0px !important;
}
.dropdown-item{
  width: 100%;
  height: 40px;
  float: left;
  background: #fff;
  line-height: 40px;
  padding-top: 0px !important;
  padding-bottom: 0px !important;
}
.dropdown-item:hover{
  background: #eee;
}
.dropdown-header{
  padding: 5px 0 10px 0;
  width: 100%;
  text-align: center;
  border-bottom: solid 1px #ccc;
}
.dropdown-item-profile{
  min-height: 100px !important;
  width: 100%;
  float: left;
  top: 0;
  overflow-y: hidden;
}

#account{
  background: #fff;
}
.account-picture{
  height: 100px;
  width: 100%;
  float: left;
  text-align: center;
  margin-top: 20px;
}
.account-picture i{
  line-height: 100px;
  font-size: 100px;
}
.account-picture img{
  width: 100px;
  height: 100px;
  height: auto;
}
.account-info{
  height: 40px;
  line-height: 40px;
  width: 100%;
  float: left;
  color: #888;
  font-weight: 550;
}
.dropdown-item-button{
  height: 50px;
  width: 100%;
  float: left;
  background: #fff;
  border-top: solid 1px #ddd;
}
.dropdown-item-button button{
  height: 40px;
  border-radius: 0;
  width: 100px;
  margin-top: 5px;  
}
.dropdown-item-button button:hover{
  background: #ff0000;
  color: #fff;
}

/*---------------------------------------------
 
 
        HEADER TOGGLER MENU


-----------------------------------------------*/
.navbar-menu-toggler-md-loggedin{
  height: 46px;
  float: left;
  text-align: center;
  font-size: 24px;
  color: #fff;
  padding: 10px 0px 10px 0px;
  display: none;
  margin-top: 5px;
  width: 40px;
  border: solid 1px #fff;
}
.navbar-menu-toggler-md-loggedin:hover{
  cursor: pointer;
  background: #1c73eb;
}

/*---------------------------------------------------------          

                  RESPONSIVE HANDLER

-----------------------------------------------------------*/

@media screen and (min-width: 768px) and (max-width: 992px){
 .desktop-loggedin{
    display: none;
  }
  .mobile-loggedin{
    display: block;
  }
  .header-holder-loggedin{
    width: 90%;
    margin-left: 5%;
    margin-right: 5%;
    margin-bottom: 0px;
  }
  .logo-loggedin{
    width: 30%;
  }
  .logo-loggedin .navbar-brand-loggedin img{
    height: 45px;
  }
  .menu-loggedin{
    width: 70%;
  }
  .navbar-menu-toggler-md-loggedin{
    display: block;
    float: left;
  }
  .navbar-nav .nav-item{
    text-align: center;
  }

  .header-primary-menu-loggedin .web-menu-loggedin{
    float: right;
    width: 40px;
  }
  .menu-loggedin .header-top-menu-loggedin{
    display: none;
  }
}

/*-------------- Small Screen --------------*/
@media screen and (min-width: 376px) and (max-width: 767px){
  .desktop-loggedin{
    display: none;
  }
  .mobile-loggedin{
    display: block;
  }
  .header-holder-loggedin{
    width: 90%;
    margin-left: 5%;
    margin-right: 5%;
    margin-bottom: 0px;
  }
  .logo-loggedin{
    width: 50%;
  }

  .menu-loggedin{
    width: 50%;
  }
  .logo-loggedin .navbar-brand-loggedin img{
    height: 45px;
  }

  .navbar-menu-toggler-md-loggedin{
    display: block;
    float: left;
  }
  .header-primary-menu-loggedin .web-menu-loggedin{
    float: right;
    width: 40px;
  }


  .navbar-nav .nav-item{
    text-align: center;
  }

  .menu-loggedin .header-top-menu-loggedin{
    display: none;
  }
}

/*-------------- Small Screen for Mobile Phones --------------*/
@media screen and (max-width: 375px){
  .desktop-loggedin{
    display: none;
  }
  .mobile-loggedin{
    display: block;
  }
  .header-holder-loggedin{
    width: 90%;
    margin-left: 5%;
    margin-right: 5%;
    margin-bottom: 0px;
  }
  .logo-loggedin{
    width: 50%;
  }

  .logo-loggedin .navbar-brand-loggedin img{
    height: 45px;
  }

  .menu-loggedin{
    width: 50%;
  }
  .navbar-menu-toggler-md-loggedin{
    display: block;
    float: left;
  }
  .header-primary-menu-loggedin .web-menu-loggedin{
    float: left;
    width: 40px;
  }
  .menu-loggedin .header-top-menu-loggedin{
    display: none;
  }
}

/*-------------- Small Screen for Mobile Phones --------------*/
@media screen and (max-width: 345px){
  .desktop-loggedin{
    display: none;
  }
  .mobile-loggedin{
    display: block;
  }
  .header-holder-loggedin{
    width: 90%;
    margin-left: 5%;
    margin-right: 5%;
    margin-bottom: 0px;
  }
  .logo-loggedin{
    width: 60%;
  }

  .logo-loggedin .navbar-brand-loggedin img{
    height: 45px;
  }

  .menu-loggedin{
    width: 40%;
  }
  .navbar-menu-toggler-md-loggedin{
    display: block;
    float: left;
  }
  .header-primary-menu-loggedin .web-menu-loggedin{
    float: left;
    width: 40px;
  }
  .menu-loggedin .header-top-menu-loggedin{
    display: none;
  }
}

</style>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
export default {
  mounted(){
    this.getAccontInfo()
    this.user.type = localStorage.getItem('account_type')
  },
  created(){
    this.display()
  },
  data(){
    return{
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      account: [],
      config: CONFIG
    }
  },
  methods: {
    logOut(){
      AUTH.deaunthenticate()
    },
    redirect(parameter){
      ROUTER.push(parameter)
    },
    display(){
    },
    getAccontInfo(){
      let parameter = {
        'condition': [{
          'column': 'id',
          'clause': '=',
          'value': this.user.userID
        }]
      }
      this.APIRequest('accounts/retrieve', parameter).then(response => {
        this.account = response.data[0]
      })
    },
    menuToggle(){
      if($('#navbarSupportedContent').hasClass('show') === true){
        $('#navbarSupportedContent').removeClass('show')
      }
    }
  }
}
</script>
