<template>
  <div class="row">
    <div class="col-lg-4 col-md-6 mx-auto login-container">
      <!-- <span class="login-company-name">
        <img src="../../assets/img/logo_talk.png"  v-if="config.SITE_FLAG === 0">
        <img src="../../assets/img/logo_talk.png"  v-if="config.SITE_FLAG === 1">
        <img src="../../assets/img/logo_habla.png" v-if="config.SITE_FLAG === 2">
      </span> -->
      <div class="login-input-holder">
        <h3>
          Reset Password Request
        </h3>
        <div class="login-fields">
          <div class="login-message-holder login-spacer" v-if="errorMessage != ''">
              <span class="text-danger text-center" v-if="successMessage === null && errorMessage !== null"><b>Oops!</b> 
                {{errorMessage}}
              </span>
              <span class="text-primary text-center" v-else>
                {{successMessage}}
              </span>
          </div>
          <input type="text" name="email" placeholder="Username or Email Address" class="form-control form-control-login login-spacer" v-model="email" v-if="successMessage === null">
          <button class="btn btn-primary btn-block btn-login login-spacer" v-on:click="requestReset()"  v-if="successMessage === null">Request to Reset</button>
          <button class="btn btn-primary btn-block btn-login login-spacer" v-if="successMessage !== null"  v-on:click="redirect('/login')">Back to Login</button>
       </div>
      </div>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import CONFIG from '../../config.js'
export default {
  name: '',
  components: {
    'input-group': require('components/input_field/InputGroup.vue')
  },
  mounted(){
  },
  data(){
    return{
      email: null,
      errorMessage: null,
      successMessage: null,
      config: CONFIG
    }
  },
  methods: {
    requestReset(){
      if(this.validate() === true){
        let parameter = {
          email: this.email,
          'host': this.config.WEBHOST,
          'api': this.config.BACKEND_URL,
          'app': this.config.WEB_APP,
          'host_email': this.config.HOST_EMAIL,
          'app_title': this.config.WEBSITE_TITLE,
          'web': this.config.WEBSITE,
          'browser': this.config.BROWSER
        }
        $('#loading').css({'display': 'block'})
        this.APIRequest('accounts/reset_request', parameter).then(response => {
          $('#loading').css({'display': 'none'})
          if(response.data === true){
            this.errorMessage = null
            this.successMessage = 'We\'ve sent to your email address.'
          }else{
            this.successMessage = null
            this.errorMessage = 'Username or Email Address did not exist!'
          }
        })
      }else{
        this.errorMessage = 'Please fill in the required field.'
      }
    },
    validate(){
      if(this.email === null || this.email === ''){
        return false
      }else{
        return true
      }
    },
    redirect(parameter){
      ROUTER.push(parameter)
    }
  }
}
</script>
<style>
body{
  background: #fff;
}

.login-container{
  margin-top: 75px;
  background: #fff;
  border: solid 1px #ddd;
  margin-bottom: 75px;
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
