<template>
  <div class="container-fluid" v-if="!tokenData.verifyingToken && !tokenData.token">
    <div class="row">
      <div class="col-md-6 col-lg-4 mx-auto login-container">
        <span class="login-company-name">
          <img src="../../assets/img/logo_talk.png"  v-if="config.SITE_FLAG === 0">
          <img src="../../assets/img/logo_talk.png"  v-if="config.SITE_FLAG === 1">
          <img src="../../assets/img/logo_habla.png" v-if="config.SITE_FLAG === 2">
        </span>
        <div class="login-input-holder">
          <h3>
            Email Verification Sent!
          </h3>
          <div class="login-fields">
            <div class="input-group login-spacer text-center login-spacer">
              <label>
                We've sent an email to your email address at <b> {{email}} </b> for verification purposes only. This may take few minutes.
              </label>
            </div>
            <button class="btn btn-danger btn-block btn-login login-spacer" v-on:click="redirect('/')">Back to Login</button>  
          </div>
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
    this.email = this.$route.params.email
  },
  data(){
    return {
      email: this.$route.params.email,
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG,
      flag: false
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    }
  }
}
</script>
<style>
body{
  background: #00bff3;
}

.login-container{
  margin-top: 75px;
  background: #00bff3;
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
