<template>
  <div class="container-fluid custom-container" v-if="!tokenData.verifyingToken && !tokenData.token">
    <div class="row">
      <div class="col-md-6 col-lg-4 mx-auto login-container">
        <!-- <span class="login-company-name">
          <img src="../../assets/img/logo_talk.png"  v-if="config.SITE_FLAG === 0">
          <img src="../../assets/img/logo_talk.png"  v-if="config.SITE_FLAG === 1">
          <img src="../../assets/img/logo_habla.png" v-if="config.SITE_FLAG === 2">
        </span> -->
        <div class="login-input-holder">
          <h3>
            Login
          </h3>
          <div class="login-fields">
            <div class="login-message-holder login-spacer" v-if="errorMessage != ''">
              <span class="text-danger"><b>Oops!</b> {{errorMessage}}</span>
            </div>
            <div class="input-group login-spacer">
              <span class="input-group-addon input-group-addon-login" id="addon-1"><i class="fa fa-user"></i></span>
              <input type="text" class="form-control form-control-login" placeholder="Username or Email" aria-describedby="addon-1" v-model="username" @keyup.enter="logIn()">
            </div>
            <div class="input-group login-spacer">
              <span class="input-group-addon input-group-addon-login" id="addon-2"><i class="fa fa-key"></i></span>
              <input type="password" class="form-control form-control-login" placeholder="********" aria-describedby="addon-2" v-model="password" @keyup.enter="logIn()">
            </div>
            <div class="links login-spacer">
              <label v-on:click="redirect('/request_reset')">
                Forgot Password?
              </label>
            </div>
            <button class="btn btn-primary btn-block btn-login login-spacer" v-on:click="logIn()">Login</button>
            <button class="btn btn-warning btn-block btn-login login-spacer" v-on:click="href(config.WEBSITE + '/pricing')">Create Account Now!</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="mobileUsersInfo">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Important</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p style="padding: 20px 10px 20px 10px;">Please do not put your device into silent mode for audio purposes.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>
<style>

body{
  background: #fff;
}

.login-container{
  margin-top: 75px;
  border: solid 1px #ddd; 
  margin-bottom: 75px;
  padding: 0px;
}

.login-company-name{
  width: 100%;
  text-align: center;
  float: left;
}

.login-company-name img{
  width: 60%;
  margin-left: 20%;
  margin-right: 20%;
}
.login-input-holder{
  width: 100%;
  float: left;
  background: #fff;
}
.login-input-holder h3{
  width: 100%;
  float: left;
  font-size: 24px;
  text-align: center;
  padding-top: 25px;
  padding-bottom: 10px;
  border-bottom: solid 1px #fff;
  color: #00bff3;
}
.login-input-holder .login-fields{
  width: 80%;
  float: left;
  margin: 25px 10% 25px 10%;
}
.login-message-holder{
  min-height: 30px;
  font-size: 12px;
  float: left;
  overflow: hidden;
}

.login-fields .links{
  float: left;
  width: 100%;
  text-align: center;
  color: #00bff3;
}
.login-fields .links label:hover{
  cursor: pointer;
}

.login-spacer{
  margin-bottom: 10px;
}

.form-control-login{
  height: 45px !important;
}

.input-group-addon-login{
  width: 45px;
}
/*----------------------------------------

            Buttons

------------------------------------------*/
.btn-login{
  height: 45px !important;
}

.btn-warning{
  color: #fff !important;
}

/*---------------------------------------------------------

                  RESPONSIVE HANDLER

-----------------------------------------------------------*/

/*-------------- Large Screens for Desktop --------------*/
@media (min-width: 1200px){

}


/*-------------- Medium Screen for Tablets  --------------*/
@media screen (min-width: 992px), screen and (max-width: 1199px){
}

/*-------------- Small Screen for Mobile Phones  --------------*/
@media screen (min-width: 768px), screen and (max-width: 991px){
 
}

/*-------------- Extra Small Screen for Mobile Phones --------------*/
@media (max-width: 991px){
  .hide-this{
    display: none;
  }
  .login-container{
    box-shadow: 0 0 0 0 #fff !important;
    margin-top: 75px !important;
    border: none;
  }
  .login-company-name, .form-check{
    text-align: center !important; 
    width: 100% !important;
  }

  .login-input-holder{
    width: 100% !important;
    margin-left: 0 !important;
  }
}
</style>

<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
export default {
  mounted(){
    this.checkSize()
  },
  data(){
    return {
      username: null,
      password: null,
      errorMessage: '',
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG
    }
  },
  methods: {
    logIn(){
      if(this.username !== null && this.username !== '' && this.password !== null && this.password !== ''){
        $('#loading').css({'display': 'block'})
        AUTH.authenticate(this.username, this.password, (response) => {
          $('body').css({'background': '#ffffff'})
          $('#loading').css({'display': 'none'})
          this.errorMessage = null
          window.location.href = window.location.href
        }, (response, status) => {
          $('#loading').css({'display': 'none'})
          this.errorMessage = (status === 401) ? 'Your username and password did not matched.' : 'Cannot log in? Contact us through email: support@talkfluent.com'
        })
      }else{
        this.errorMessage = 'Please fill in all required fields.'
      }
    },
    href(url){
      window.location.href = url
    },
    redirect(parameter){
      ROUTER.push(parameter)
    },
    request(parameter){
      this.APIRequest(parameter, {}).then(response => {
      })
    },
    checkSize(){
      let uA = navigator.userAgent
      if(window.screen.availWidth < 992 || uA.match(/(iPhone|iPod|iPad)/)){
        $('#mobileUsersInfo').modal('show')
      }
    }
  }
}
</script>
