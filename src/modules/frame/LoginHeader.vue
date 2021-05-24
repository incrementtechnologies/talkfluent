 <template>
  <div class="header-container">
    <div class="header-holder">
    <div class="logo">
      <a class="navbar-brand" v-on:click="redirect('/dashboard')">
        <img src="../../assets/img/logo_talk.png"  v-if="config.SITE_FLAG === 0">
        <img src="../../assets/img/logo_talk.png"  v-if="config.SITE_FLAG === 1">
        <img src="../../assets/img/logo_habla.png" v-if="config.SITE_FLAG === 2">
      </a>
    </div>
    <div class="menu">
      <!-- <span class="header-top-menu">
        <span>1800</span> <span>000</span> <span>1982</span>
      </span> -->
      <span class="header-primary-menu">
        <i class="fa fa-bars navbar-menu-toggler-md" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"></i>
        <ul class="web-menu">
          <a v-on:click="redirect('/')" target="_BLANK" class="desktop"><li class="item"><button class="btn-custom btn-white"><b>Login</b></button></li></a>
          <a v-bind:href="config.WEBSITE + '/pricing'" target="_BLANK" class="desktop"><li class="item"><button class="btn-custom btn-green"><b>Get Started</b></button></li></a>
          <a v-bind:href="config.WEBSITE + '/contact'" target="_BLANK" class="desktop"><li class="item"><b>Contact Us</b></li></a>
          <a v-bind:href="config.WEBSITE + '/faq'" target="_BLANK" class="desktop"><li class="item"><b>FAQ</b></li></a>
          <a v-bind:href="config.WEBSITE + '/pricing'" target="_BLANK" class="desktop"><li class="item"><b>Pricing</b></li></a>
          <a v-bind:href="config.WEBSITE + '/about'" target="_BLANK" class="desktop"><li class="item"><b>About Us</b></li></a>
          <a v-bind:href="config.WEBSITE" target="_BLANK" class="desktop"><li class="item"><b>Home</b></li></a>
        </ul>
        </span>
      </div>
    </div>
    <div class="collapse navbar-collapse navbar-menu mobile-toggle" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <a v-bind:href="config.WEBSITE + '/pricing'" target="_BLANK" class="nav-item" v-on:click="menuToggle()">
          <li><b>Get Started</b></li>
        </a>
        <a class="nav-item" v-on:click="redirect('/'), menuToggle()">
          <li><b>Login</b></li>
        </a>
        <a v-bind:href="config.WEBSITE" target="_BLANK" class="nav-item" v-on:click="menuToggle()">
          <li><b>Home</b></li>
        </a>
        <a v-bind:href="config.WEBSITE + '/about'" target="_BLANK" class="nav-item" v-on:click="menuToggle()">
          <li><b>About Us</b></li>
        </a>
        <a v-bind:href="config.WEBSITE + '/pricing'" target="_BLANK" class="nav-item" v-on:click="menuToggle()">
          <li><b>Pricing</b></li>
        </a>
        <a v-bind:href="config.WEBSITE + '/faq'" target="_BLANK" class="nav-item" v-on:click="menuToggle()">
          <li><b>FAQ</b></li>
        </a>
        <a v-bind:href="config.WEBSITE + '/contact'" target="_BLANK" class="nav-item" v-on:click="menuToggle()">
          <li><b>Contact Us</b></li>
        </a>
      </ul>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
export default {
  mounted(){
    this.getAccontInfo()
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
      ROUTER.push('/login')
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
<style scoped>

/*
        BODY
*/
body{
  font-size: 13px;
  font-weight: 400;
}

.btn{
  font-size: 12px;
}
.btn:hover{
  cursor: pointer;
}


/*
        BUTTONS
*/
.btn-primary{
  background: #00bff3;
  border-color: #00bff3;
}

.btn-primary:hover{
  background: #00bff3;
  border-color: #00bff3;
}

.btn-danger{
  background: #aa0000;
}

.btn-danger:hover{
  background: #ff0000;
  border-color: #ff0000;
}
.btn-facebook{
  background: #124abf;
  color: #fff;
}

.btn-facebook:hover{
  background: #3b5998;
}

.btn-gmail{
  background: #c70000;
  color: #fff;
}
.btn-gmail:hover{
  background: #c71610;
}
/*
      HALLOW

*/

.btn-primary-hallow{
  border-color: #00bff3;
  color: #00bff3;
  background: #fff;
}
.btn-primary-hallow:hover{
  color: #00bff3;
  border-color: #00bff3;
}
.btn-danger-hallow{
  border-color: #aa0000;
  background: #fff;
  color: #aa0000;
}
.btn-danger-hallow:hover{
  color: #ff0000;
  border-color: #ff0000;
}
.account-picture i{
  font-size: 100px !important;
}


/*------------------------------------

          TABLES

--------------------------------------*/

.table{
  font-size: 12px;
}

/*----------------------------------------

            Forms

------------------------------------------*/
.form-control{
  height: 35px;
  font-size: 12px;
}

/*---------------------------------------------
 

        HEADER


-----------------------------------------------*/
.header-container{
  background:#00bff3;
  width: 100%;
  min-height: 70px;
  overflow-y: hidden;
  float: left;
}
.header-holder{
  width: 70%;
  min-height: 70px;
  overflow-y: hidden;
  float: left;
  margin: 15px 15% 12px 15%;
}
.logo{
  float: left;
  height: 50px;
  font-size: 24px;
  width: 30%;
  background: #00bff3;
  text-align: left;
}
.menu{
  background: #00bff3;
  min-height: 50px;
  float: left;
  width: 70%;
}
.menu .header-top-menu{
  float: left;
  width: 100%;
  text-align: right;
  color: #fff;
}
.menu .header-primary-menu{
  height: 50px;
  float: left;
  width: 100%;
  overflow-y: hidden;
  margin-top: 0px !important;
}


/*-- navbar --*/
.header-primary-menu .fa-bars{
  padding-top: 16px;
}
.logo .navbar-brand img{
  color: #fff;
  height: 65px;
}
.navbar-menu{
  width: 100%;
  height: auto;
  z-index: 100000 !important;
  background: #fff;
  color: #00bff3;
  position: absolute;
  margin-top: 85px;
}
.navbar-menu ul{
  width: 100%;
  float:left;
}
.navbar-menu ul .nav-item{
  width: 100%;
  color: #00bff3;
  background: #fff;
}
.navbar-menu ul .nav-item li{
  padding-top: 15px;
}

  

/*---------------------------------------------
       


                  ICONS


-----------------------------------------------*/

#messagesHeader{
  margin-left: 70%;
}
.nav-item{
  width: 5%;
  height: 50px;
  text-align: right;
  float: right;
  color: #fff;
  display: inline;
}

.web-menu{
  list-style: none;
  width: 100%;
  float: right !important;
  padding: 0px !important;
  min-height: 50px;
  overflow-y: hidden;
}
.web-menu .item{
  padding-left: 25px;
  padding-bottom: 13px;
  padding-top: 13px;
  float:right;
  color:#fff;
  font-size: 16px;
  line-height: 37px;
}
.web-menu .dropdown .fa-cog{
  font-size: 24px;
  padding-bottom: 14px;
  padding-top: 12px;
  padding-left: 10px;
  padding-right: 10px;
  float: right;
  color:#fff;
  border:solid 1px #fff;
}
.web-menu li:hover{
  text-decoration: underline;
}
.web-menu .dropdown .fa-cog:hover{
  cursor: pointer;
  background: #1c73eb !important;
  color: #fff !important;
}
.nav-item span i{
  padding: 12px 0 15px 0;
  font-size: 24px;
}

.desktop{
  display: block;
}
.mobile{
  display: none;
}

.nav-item .label{
  z-index: 1000;
  background: #ff0000;
  padding: 5px;
  margin: -10px 0 0 -10px;
  border-radius: 2px;
  border-color: solid 1px #ff0000;
}

.nav-item:hover{
  background: #1c73eb;
  cursor: pointer;
}

.settings-dropdown:hover{
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
}
.dropdown-item{
  width: 100%;
  height: 40px;
  float: left;
  background: #fff;
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
  height: 150px;
  width: 100%;
  float: left;
}
.account-info{
  height: 50px;
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
.navbar-menu-toggler-md{
  height: 50px;
  float: left;
  text-align: center;
  font-size: 16px;
  color: #fff;
  padding: 10px 0 15px 0;
  display: none;
  border: solid 1px #fff;
}
.navbar-menu-toggler-md:hover{
  cursor: pointer;
  background: #1c73eb;
}


/*---------------------------------------------------------          

                  RESPONSIVE HANDLER

-----------------------------------------------------------*/

@media screen and (min-width: 768px) and (max-width: 992px){
 .desktop{
    display: none;
  }
  .mobile{
    display: block;
  }
  .header-holder{
    width: 90%;
    margin-left: 5%;
    margin-right: 5%;
    margin-bottom: 0px;
  }
  .logo{
    width: 29%;
    margin-left: 1%;
  }
  
  .logo .navbar-brand img{
    height: 50px;
  }

  .menu{
    width: 69%;
    margin-right: 1%;
  }
  .navbar-menu-toggler-md{
    display: block;
    float: right;
    padding-left: 15px;
    padding-right: 15px;
  }

  .menu .header-top-menu{
    display: none;
  }

  .navbar-menu ul .nav-item{
    text-align: center;
  }
}

/*-------------- Small Screen --------------*/
@media screen and (min-width: 376px) and (max-width: 767px){
  .desktop{
    display: none;
  }
  .mobile{
    display: block;
  }
  .header-holder{
    width: 90%;
    margin-left: 5%;
    margin-right: 5%;
    margin-bottom: 0px;
  }
  .logo{
    width: 49%;
    margin-left: 1%;
  }
  .logo .navbar-brand img{
    height: 45px;
  }
  .menu{
    width: 49%;
    margin-right: 1%;
  }
  .navbar-menu-toggler-md{
    display: block;
    padding-left: 15px;
    padding-right: 15px;
    float: right;
  }

  .menu .header-top-menu{
    display: none;
  }

  .navbar-menu ul .nav-item{
    text-align: center;
  }
}

/*-------------- Small Screen for Mobile Phones --------------*/
@media screen and (max-width: 375px){
  .desktop{
    display: none;
  }
  .mobile{
    display: block;
  }
  .header-holder{
    width: 90%;
    margin-left: 5%;
    margin-right: 5%;
    margin-bottom: 0px;
  }
  .logo{
    width: 59%;
    margin-left: 1%;
  }
  .logo .navbar-brand img{
    height: 45px;
  }
  .menu{
    width: 39%;
    margin-right: 1%;
  }
  .navbar-menu-toggler-md{
    display: block;
    float: right;
    padding-left: 15px;
    padding-right: 15px;
  }
  .header-primary-menu .web-menu{
    width: 40%;
    float: left;
  }
  .menu .header-top-menu{
    display: none;
  }
  .navbar-menu ul .nav-item{
    text-align: center;
  }
}

</style>
