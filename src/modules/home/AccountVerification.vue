<template>
  <div class="row">
    <div class="col-md-6 col-lg-4 mx-auto login-container">
<!--       <span class="login-company-name">
          <img src="../../assets/img/logo_talk.png"  v-if="config.SITE_FLAG === 0">
          <img src="../../assets/img/logo_talk.png"  v-if="config.SITE_FLAG === 1">
          <img src="../../assets/img/logo_habla.png" v-if="config.SITE_FLAG === 2">
      </span> -->
      <div class="login-input-holder">
        <h3>
          Verification
        </h3>
        <div class="login-fields">
          <div class="login-message-holder login-spacer" v-if="errorMessage !== null">
              <span class="text-danger"><b>Oops!</b> {{errorMessage}}</span>
          </div>
          <div class="input-group login-spacer text-center">
              {{text}}
          </div>
          <button class="btn btn-primary btn-block btn-login login-spacer" v-on:click="verify()" v-if="flag === false">Verify</button>
          <button class="btn btn-primary btn-block btn-login login-spacer" v-on:click="redirect('/dashboard')" v-if="flag === true && user.userID !== 0">Continue to Dashboard</button>
          <button class="btn btn-primary btn-block btn-login login-spacer" v-on:click="redirect('/login')" v-if="flag === true && user.userID === 0">Go to Login</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
export default {
  mounted(){
    this.username = this.$route.params.username
    this.code = this.$route.params.code
    console.log(this.username)
  },
  data(){
    return {
      username: this.$route.params.username,
      code: this.$route.params.code,
      errorMessage: null,
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG,
      flag: false,
      text: 'Verify your account by clicking the button bellow.'
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    verify(){
      let parameter = {
        'username': this.username,
        'code': this.code
      }
      this.APIRequest('accounts/verify', parameter).then(response => {
        if(response.data === true){
          this.text = 'You\'ve successully verified your account.'
          this.errorMessage = null
          this.flag = true
        }else{
          this.flag = false
          this.errorMessage = 'Unable to Verified this account. Kindly go to your email and check the verification link.'
        }
      })
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
  margin-top: 10px;
  background: #fff;
  margin-bottom: 75px;
  border: solid 1px #ddd;
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
