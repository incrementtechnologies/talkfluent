<template>
  <div class="container-fluid custom-container" v-if="!tokenData.verifyingToken && !tokenData.token">
    <div class="row">
      <div class="col-md-6 col-lg-4 mx-auto login-container">
<!--         <span class="login-company-name">
          <img src="../../assets/img/logo_talk.png"  v-if="config.SITE_FLAG === 0">
          <img src="../../assets/img/logo_talk.png"  v-if="config.SITE_FLAG === 1">
          <img src="../../assets/img/logo_habla.png" v-if="config.SITE_FLAG === 2">
        </span> -->
        <div class="login-input-holder">
          <ul class="stages-menu">
            <li v-bind:class="{'active-stage-menu': stageIndex === 0, 'inactive-stage-menu': stageIndex !== 0}" v-on:click="setStageIndex(0)">
              <span>
                <label v-bind:class="{'active-step': stageIndex === 0, 'inactive-step': stageIndex !== 0}" style="">1</label>
              </span>
              <h4>Plan</h4>
            </li>
            <li v-bind:class="{'active-stage-menu': stageIndex === 1, 'inactive-stage-menu': stageIndex !== 1}" v-on:click="setStageIndex(1)">
              <span>
                <label v-bind:class="{'active-step': stageIndex === 1, 'inactive-step': stageIndex !== 1}" style="">2</label>
              </span>
              <h4>Account</h4>
            </li>
            <li v-bind:class="{'active-stage-menu': stageIndex === 2, 'inactive-stage-menu': stageIndex !== 2}" v-on:click="setStageIndex(2)">
              <span>
                <label v-bind:class="{'active-step': stageIndex === 2, 'inactive-step': stageIndex !== 2}" style="">3</label>
              </span>
              <h4>Billing</h4>
            </li>
            <li v-bind:class="{'active-stage-menu': stageIndex === 3, 'inactive-stage-menu': stageIndex !== 3}" v-on:click="setStageIndex(3)">
              <span>
                <label v-bind:class="{'active-step': stageIndex === 3, 'inactive-step': stageIndex !== 3}" style="">4</label>
              </span>
              <h4>Payment</h4>
            </li>
          </ul>

          <div class="login-message-holder text-center" v-if="errorMessage != null">
            <span class="text-danger"><b>Oops!</b> {{errorMessage}}</span>
          </div>
          <div class="login-message-holder text-center" v-if="successMessage != null">
            <span class="text-success">{{successMessage}}</span>
          </div>
          <span class="plan-details" v-bind:class="{'bg-blue': plan === 'MONTHLY', 'bg-green': plan === 'SEMI_ANNUAL', 'bg-orange': plan === 'ANNUALLY'}" v-if="stageIndex === 0">
            <i class="fas fa-shopping-cart details-icon"></i>
            <label>{{planDetails}}</label>
          </span>




          <div class="login-fields" v-if="stageIndex === 0">
            <span class="plan-cost">
              <span class="item">
                <label class="pull-left">Total</label>
                <label class="pull-right">$ {{total}} / Month</label>
              </span>
              <span class="item" v-if="save !== null">
                <label class="pull-left">Save</label>
                <label class="pull-right">$ {{save}} / Year</label>
              </span>
              <span class="item">
                <label class="pull-left">First 7 Days</label>
                <label class="pull-right">$ 1.00</label>
              </span>
            </span>
            <span class="trial-cost">
              <label class="pull-left">TO PAY TODAY</label>
              <label class="pull-right">$ 1.00</label>
            </span>
            <span></span>
            <button class="btn btn-primary btn-block btn-login login-spacer" v-on:click="next()">CONTINUE</button>
          </div>



          


          <div class="login-fields" v-if="stageIndex === 1">
            
            <div class="row">
              <div class="form-group login-spacer col-lg-6 col-lg-6 col-md-12 col-sm-12">
                <label for="first_name">First Name <label class="text-danger">*</label></label>
                <input type="text" class="form-control form-control-login" id="first_name" placeholder="Enter First Name" v-model="account.first_name">
              </div>

              <div class="form-group login-spacer col-lg-6 col-md-12 col-sm-12">
                <label for="last_name">Last Name <label class="text-danger">*</label></label>
                <input type="text" class="form-control form-control-login" id="last_name" placeholder="Enter Last Name" v-model="account.last_name">
              </div>
            </div>

            <div class="row">
              <div class="form-group login-spacer col-lg-12 col-md-12 col-sm-12">
                <label for="email">Email  <label class="text-danger">[Use this on Login]</label></label>
                <input type="email" class="form-control form-control-login" id="email" placeholder="Enter Email Address" v-model="account.email">
              </div>
            </div>

            <div class="row">
              <div class="form-group login-spacer col-lg-12 col-md-12 col-sm-12">
                <label for="username">Username <label class="text-danger">[Use this on Login]</label></label>
                <input type="text" class="form-control form-control-login" id="username" placeholder="Enter Username" v-model="account.username">
              </div>
            </div>

            <div class="row">
              <div class="form-group login-spacer col-lg-12 col-md-12 col-sm-12">
                <label for="password">Password  <label class="text-danger">[Use this on Login]</label></label>
                <input type="password" class="form-control form-control-login" id="password" placeholder="Enter Password" v-model="account.password">
              </div>
            </div>

            <div class="row">
              <div class="form-group login-spacer col-lg-12 col-md-12 col-sm-12">
                <label for="cpassword">Confirm Password <label class="text-danger">*</label></label>
                <input type="password" class="form-control form-control-login" id="cpassword" placeholder="Confirm Your Password" v-model="account.cpassword" @keyup.enter="next()">
              </div>
            </div>
            
            
            <button class="btn btn-primary btn-block btn-login login-spacer" v-on:click="next()">CONTINUE</button>
          </div>



          <div class="login-fields" v-if="stageIndex === 2">


            <div class="row">
              <div class="form-group login-spacer col-lg-12 col-md-12 col-sm-12">
                <label for="company">Company</label>
                <input type="text" class="form-control form-control-login" id="company" placeholder="Enter Company(Optional)" v-model="billing.company">
              </div>
            </div>

            <div class="row">
              <div class="form-group login-spacer col-lg-12 col-md-12 col-sm-12">
                <label for="address">Address <label class="text-danger">*</label></label>
                <input type="text" class="form-control form-control-login" id="address" placeholder="Enter Address" v-model="billing.address">
              </div>
            </div>


            <div class="row">
              <div class="form-group login-spacer col-lg-6 col-md-12 col-sm-12">
                <label for="city">City <label class="text-danger">*</label></label>
                <input type="text" class="form-control form-control-login" id="city" placeholder="Enter City" v-model="billing.city">
              </div>
              <div class="form-group login-spacer col-lg-6 col-md-12 col-sm-12">
                <label for="postal_code">Postal Code <label class="text-danger">*</label></label>
                <input type="text" class="form-control form-control-login" id="postal_code" placeholder="Enter Postal Code" v-model="billing.postal_code">
              </div>
            </div>


            <div class="row">
              <div class="form-group login-spacer col-lg-6 col-md-12 col-sm-12">
                <label for="country">Country <label class="text-danger">*</label></label>
                <select class="form-control form-control-login" v-model="billing.country" id="country" v-on:change="getStates()">
                  <option v-bind:value="item.countryCode" v-for="item in countries">{{item.name}}</option>
                </select>
              </div>
              <div class="form-group login-spacer col-lg-6 col-md-12 col-sm-12">
                <label for="state">State <label class="text-danger">*</label></label>
                <select class="form-control form-control-login" v-model="billing.state" id="state" v-if="states !== null">
                  <option v-bind:value="item.label" v-for="item, index in states" v-if="item.label !== null && item.label !== 'undefined' && item.label !== undefined">{{item.label}}</option>
                </select>
                <input type="text" class="form-control form-control-login" v-model="billing.state" v-else placeholder="Enter State or Province">
              </div>
            </div>

            <button class="btn btn-primary btn-block btn-login login-spacer" v-on:click="next()">CONTINUE</button>
          </div>




          <div class="login-fields" v-if="stageIndex === 3">
            <div class="radio">
              <label @click="paymentMethod = 'credit_card'" class="payment-methods-label"><i :class="paymentMethod === 'credit_card' ? 'fa fa-square' : 'far fa-square'" style="padding-right: 10px;"></i>Credit Card</label>
              <div :class="{complete}" v-if="paymentMethod === 'credit_card'" >
                <div class="row">
                  <div class="form-group login-spacer col-lg-12 col-md-12 col-sm-12">
                    <label for="address">Card Number</label>
                    <card-number class="stripe-element card-number"
                      ref="cardNumber"
                      :stripe="stripeSK"
                      @change="number = $event.complete"
                      :options="options"
                    />
                  </div>
                </div>

                <div class="row">
                  <div class="form-group login-spacer col-lg-6 col-md-6 col-sm-12">
                    <label for="address">Expiration</label>
                    <card-expiry class="stripe-element card-expiry"
                      ref="cardExpiry" 
                      :stripe="stripeSK" 
                      @change="expiry = $event.complete"
                      :options="options"
                    />
                  </div>

                  <div class="form-group login-spacer col-lg-6 col-md-6 col-sm-12">
                    <label for="address">CVC</label>
                    <div id="signup-card-number">
                      <card-cvc class='stripe-element card-cvc'
                        ref='cardCvc'
                        :stripe="stripeSK" 
                        @change="expiry = $event.complete" 
                        :options="options"
                      />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group login-spacer col-lg-8 col-md-8 col-sm-12">
                    <label for="coupon">Coupon</label>
                    <input class="form-control form-control-login" id="coupon" placeholder="Enter Coupon" v-model="payment.coupon" :disabled="successMessage !== null" type="text">
                  </div>
                  <div class="login-spacer col-lg-4 col-md-4 col-sm-12">
                    <button class="btn btn-block btn-primary btn-login" style="margin-top: 27px;" v-on:click="checkCoupon()" v-if="successMessage === null"> APPLY</button>
                    <button class="btn btn-block btn-danger btn-login" style="margin-top: 27px;" v-on:click="deleteCoupon()" v-if="successMessage !== null"> DELETTE</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="radio">
              <label @click="paymentMethod = 'paypal'" class="payment-methods-label"><i :class="paymentMethod === 'paypal' ? 'fa fa-square' : 'far fa-square'"  style="padding-right: 10px;"></i>PayPal</label>
              <!-- <div class="row" v-if="paymentMethod === 'paypal'">
                <div class="form-group login-spacer col-lg-12 col-md-12 col-sm-12">
                  <label for="nickname">Nickname</label>
                  <input type="text" class="form-control form-control-login" id="nickname" placeholder="Enter nickname(Optional)" v-model="paypal.nickname">
                </div>
              </div> -->
            </div>
            <!-- v-b-tooltip.hover title="$1 for the first 7 days, then $149/month thereafter. You can change plans or cancel at any time" placement="topright" -->
            <h6 class="payment-methods-label">
              <!-- <label @click="paymentMethod = 'paypal'" class="payment-methods-label"><i :class="paymentMethod === 'paypal' ? 'fa fa-square' : 'far fa-square'"  style="padding-right: 10px;"></i>PayPal</label> -->
              <!-- <input type="checkbox" v-model="billingTerms" name="terms"/> -->
              <i :class="billingTerms === true ? 'fa fa-square' : 'far fa-square'"  style="padding-right: 10px;" @click="billingTerms = !billingTerms"></i>
              I Agree to the <a v-bind:href="config.WEBSITE + '/terms-and-conditions'" target="_BLANK">Terms and Conditions</a>
              <!-- <i class="fa fa-info-circle pull-right"></i> -->
            </h6>
            <button class="btn btn-primary btn-block btn-login login-spacer" v-on:click="next()" v-if="paymentMethod === 'credit_card'"><label class="pull-left" style="font-size: 16px;padding-top: 3px;">PAY via Credit Card</label><label class="pull-right" style="font-size: 16px;padding-top: 3px;">$1.00</label></button>
            <button class="btn btn-primary btn-block btn-login login-spacer" v-on:click="next()" v-if="paymentMethod === 'paypal'"><label class="pull-left" style="font-size: 16px;padding-top: 3px;">PAY via Paypal</label><label class="pull-right" style="font-size: 16px;padding-top: 3px;">$1.00</label></button>
          </div>

        </div>
      </div>
    </div>



    <div class="modal" tabindex="-1" role="dialog"  id="couponModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-danger" style="color: #fff;">
            <h5 class="modal-title">Coupon Code</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p style="padding: 25px 10px 10px 10px;">You have entered an invalid coupon code.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="resetCoupon()">Reset</button>
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
  padding: 0px;
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
  margin-top: 10px;
  background: #fff;
}

.login-input-holder .stages-menu, .login-input-holder .stage-top{
  padding: 0px;
  width: 96%;
  float: left;
  list-style: none;
  margin-left: 2%;
  margin-right: 2%;
  margin-bottom: 0px;
}

.login-input-holder .stage-top label{
  padding-top: 10px;
}

.login-input-holder .stages-menu li{
  width: 25%;
  float: left;
  text-align: center;
  padding-top: 10px;
  padding-bottom: 10px;

}

.stages-menu li:hover{
  cursor: pointer;
}

.stages-menu li span{
  float: left;
  width: 100%;
}

.stages-menu li span label{
  width: 30px;
  height: 30px;
  border-radius: 50%;
  line-height: 30px;
}

.inactive-step{
  background: #ccc;
  color: #fff;
}

.active-step{
  background: #00bff3;
  color: #fff;
}

.stages-menu li i{
  padding-top: 10px;
  font-size: 20px;
}

.stages-menu li h4{
  padding-top: 10px;
  font-size: 15px;
}

.inactive-stage-menu{
  border-bottom: 0px;
  color: #ccc;
}

.active-stage-menu{
  color: #00bff3;
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

 .login-input-holder .plan-details{
  height: 120px;
  float: left;
  width: 100%;
  border-left: solid 1px #fff;
  border-right: solid 1px #fff;
  color: #fff;
}

.plan-details .details-icon{
  width: 100px;
  float: left;
  border-radius: 50%;
  border: solid 1px #fff;
  height: 100px;
  margin: 10px;
  text-align: center;
  font-size: 50px;
  padding-top: 25px;
}

.plan-details label{
  text-transform: UPPERCASE;
  font-size: 30px;
  padding-top: 35px;
}

.login-fields .plan-cost{
  min-height: 50px;
  overflow: hidden;
  float: left;
  width: 100%;
}

.plan-cost .item{
  width: 100%;
  float: left;
}

.login-fields .trial-cost{
  float: left;
  width: 100%;
  border-top: solid 1px #ccc;
  font-size: 15px;
  font-weight: 600;
}

.login-message-holder{
  min-height: 30px;
  float: left;
  overflow: hidden;
  width: 100%;
}

.login-fields .brand-icons i{
  font-size: 24px;
}

.login-fields .links{
  float: left;
  width: 100%;
  text-align: center;
  color: #00bff3;
}

.login-fields h6{
  width: 100%;
  float: left;
  min-height: 10px;
  overflow: hidden;
  font-size: 12px;
  font-weight: 600;
  padding-top: 10px;
  padding-bottom: 10px;
  font-weight: 500;
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


.bg-green{
  background: #8fc320;
}

.bg-orange{
  background: #f8b62a;
}

.bg-blue{
  background: #00bff3;
}

.text-black{
  color: #000;
}

.text-gray{
  color: #ccc;
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

.stripe-element{
  height: 45px;
  display: block;
  width: 100%;
  padding: .5rem .75rem;
  font-size: 12px;
  background-color: #fff;
  background-image: none;
  background-clip: padding-box;
  border: 1px solid rgba(0,0,0,.15);
  border-radius: .25rem;
  transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}

.stripe-element.card-number, .stripe-element.card-expiry, .stripe-element.card-cvc{
  padding: 0px;
}

.StripeElement{
  padding: .90rem .75rem;
  color: #495057;
  line-height: 1.25;
}

.fa-square{
  font-size: 16px;
  color: #00bff3;
}

.payment-methods-label{
  cursor: pointer;
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
import axios from 'axios'
import CONFIG from '../../config.js'
import OPKEYS from '../../payment.js'
import PRODUCTS from '../../products.js'
import EasyCountriesList from 'easy-countries-list'
import CardExpiry from '../../components/stripe/CardExpiry'
import CardCvc from '../../components/stripe/CardCvc'
import CardNumber from '../../components/stripe/CardNumber'
import { Stripe } from '../../components/stripe/stripeElements'
import { PayPalPermission } from '../../components/paypal/index.js'
export default {
  mounted(){
    this.plan = (this.$route.params.plan == null) ? 'MONTHLY' : this.$route.params.plan
    this.getTotal()
  },
  data(){
    return {
      account: {
        username: null,
        password: null,
        cpassword: null,
        email: null,
        plan: null,
        first_name: null,
        last_name: null
      },
      billing: {
        company: null,
        address: null,
        city: null,
        postal_code: null,
        country: null,
        state: null
      },
      payment: {
        coupon: null,
        number_of_months: 0,
        amount_per_month: 100,
        total_amount: 100,
        taxes_and_fees: 0,
        discount_total_amount: 0,
        payment_method: null,
        currency: AUTH.currency
      },
      billingTerms: null,
      plan: (this.$route.params.plan == null) ? 'MONTHLY' : this.$route.params.plan,
      errorMessage: null,
      successMessage: null,
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG,
      flag: false,
      stageIndex: 0,
      total: null,
      save: null,
      planDetails: null,
      currentYear: parseInt(new Date().getYear()) + 1899,
      months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      paymentMethod: 'credit_card',
      countries: EasyCountriesList.getAllCountries(),
      states: null,
      complete: false,
      number: false,
      expiry: false,
      cvc: false,
      stripeSK: (OPKEYS.flag === false) ? OPKEYS.stripe.dev_pk : OPKEYS.stripe.live_pk,
      options: {
        style: {base: {
          fontSize: '16px'
        }}
      },
      credentials: {
        sandbox: '<sandbox client id>',
        production: '<production client id>'
      },
      paypal: {
        nickname: null
      }
    }
  },
  components: { CardNumber, CardCvc, CardExpiry },
  methods: {
    test1(){
    },
    getTotal(){
      switch(this.plan){
        case 'MONTHLY': this.total = PRODUCTS.monthly
          this.save = null
          this.planDetails = 'MONTHLY'
          this.account.plan = 'monthly'
          break
        case 'ANNUALLY': this.total = PRODUCTS.annually
          this.save = 176.00
          this.planDetails = 'ANNUALLY'
          this.account.plan = 'annually'
          break
      }
    },
    setStageIndex(index){
      if(this.stageIndex >= index){
        this.stageIndex = index
        this.errorMessage = null
      }else{
        this.errorMessage = 'Please click the Continue Button.'
      }
    },
    next(){
      let test = false
      if(this.stageIndex === 1){
        if(test === false){
          if(this.validateAccount() === true){
            this.errorMessage = null
            this.stageIndex ++
          }
        }else{
          this.errorMessage = null
          this.stageIndex++
        }
      }else if(this.stageIndex === 2){
        if(test === false){
          if(this.validateBilling() === true){
            this.errorMessage = null
            this.stageIndex ++
          }
        }else{
          this.errorMessage = null
          this.stageIndex++
        }
      }else if(this.stageIndex === 3){
        if(this.paymentMethod === 'credit_card'){
          if(this.validatePaymentStripe() === true){
            this.errorMessage = null
          }
        }else if(this.paymentMethod === 'paypal'){
          if(this.validatePaymentPaypal() === true){
            this.errorMessage = null
          }
        }
      }else{
        this.errorMessage = null
        this.stageIndex++
      }
    },
    validateAccount(){
      let a = this.account
      if(a.first_name === null || a.first_name === '' || a.last_name === null || a.last_name === '' || a.username === null || a.username === '' || a.email === null || a.email === '' || a.password === null || a.password === ''){
        this.errorMessage = 'Please fill in all required fields.'
        return false
      }else if(AUTH.validateEmail(a.email) === false){
        this.errorMessage = 'Please enter a valid email address.'
      }else if(a.username.length < 6){
        this.errorMessage = 'Username must not less than to 6 digits characters.'
        return false
      }else if(/^.*(?=.{8,})((?=.*[!@#$%^&*()\-_=+{};:,<.>]){1})(?=.*\d)((?=.*[a-z]){1})((?=.*[A-Z]){1}).*$/.test(a.password) === false || a.password.length < 6){
        this.errorMessage = 'Password must be atleast 6 alphanumeric characters. It should contain 1 number, 1 special character and 1 capital letter.'
        return false
      }else if(a.password !== a.cpassword){
        this.errorMessage = 'Please confirm your password.'
        return false
      }else{
        let parameter = {
          condition: [{
            clause: '=',
            value: a.username,
            column: 'username'
          }]
        }
        $('#loading').css({'display': 'block'})
        this.APIRequest('accounts/retrieve', parameter).done(response => {
          if(response.data.length > 0){
            $('#loading').css({'display': 'none'})
            this.errorMessage = 'The username has already been taken.'
            return false
          }else{
            let parameter = {
              condition: [{
                clause: '=',
                value: a.email,
                column: 'email'
              }]
            }
            this.APIRequest('accounts/retrieve', parameter).done(response => {
              $('#loading').css({'display': 'none'})
              if(response.data.length > 0){
                this.errorMessage = 'The email has already been taken.'
                return false
              }else{
                this.errorMessage = null
                this.stageIndex++
                return true
              }
            })
          }
        })
      }
    },
    validateBilling(){
      let b = this.billing
      if(b.address === null || b.address === '' || b.city === null || b.city === '' || b.postal_code === null || b.postal_code === '' || b.country === null || b.country === '' || b.state === null || b.state === ''){
        this.errorMessage = 'Please fill in all required fields.'
        return false
      }else{
        this.errorMessage = null
        return true
      }
    },
    validatePaymentStripe(){
      if(this.billingTerms === false || this.billingTerms === null){
        this.errorMessage = 'Please agree the Billing Terms.'
        return false
      }else{
        Stripe.createSource().then(data => {
          this.errorMessage = null
          if(data.error !== undefined){
            this.errorMessage = data.error.message
            return false
          }else{
            this.test(data.source)
            return true
          }
        })
      }
    },
    validatePaymentPaypal(){
      if(this.billingTerms === false || this.billingTerms === null){
        this.errorMessage = 'Please agree the Billing Terms.'
        return false
      }else{
        this.errorMessage = null
        this.setPayPalAgreement()
      }
    },
    test(parameter){
      if(this.errorMessage === null){
        this.signUp(parameter)
      }
    },
    signUp(stripeSource){
      $('#loading').css({'display': 'block'})
      this.payment.payment_method = this.paymentMethod
      let parameter = {
        'account': this.account,
        'billing': this.billing,
        'stripe_source': stripeSource,
        'payment': this.payment,
        'payment_keys': OPKEYS,
        'products': PRODUCTS,
        'host': this.config.HOST
      }
      this.APIRequest('accounts/create', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.error.status === 100){
          let message = response.error.message
          if(typeof message.username !== undefined && typeof message.username !== 'undefined'){
            this.errorMessage = message.username[0]
          }else if(typeof message.email !== undefined && typeof message.email !== 'undefined'){
            this.errorMessage = message.email[0]
          }else if(typeof message.coupon !== undefined && typeof message.coupon !== 'undefined'){
            this.errorMessage = message.coupon
          }
        }else if(response.data !== null){
          this.login(null)
        }
      })
    },
    getStates(){
      let key = 'd1c4bd17024a50b0f5e08a55b902c817'
      let ID = 254
      axios.get('https://ezcmd.com/apps/api_ezhigh/get_hierarchy/' + key + '/' + ID + '?country_code=' + this.billing.country + '&level=1').then(response => {
        this.states = response.data
      })
    },
    redirect(parameter){
      ROUTER.push(parameter)
    },
    validate(){
      let acc = this.account
      if(acc.username != null && acc.email !== null && acc.password !== null && acc.password.localeCompare(acc.cpassword) === 0 && acc.plan !== null){
        return true
      }else if(acc.username.length < 6){
        this.errorMessage = 'Username must be atleast 6 characters.'
        return false
      }else if(acc.password.length < 6){
        this.errorMessage = 'Password must be atleast 6 characters.'
        return false
      }else if(acc.password.localeCompare(acc.cpassword) !== 0){
        this.errorMessage = 'Password didnot matched.'
        return false
      }else{
        return false
      }
    },
    login(url){
      let acc = this.account
      AUTH.authenticate(acc.username, acc.password, (response) => {
        if(url === null){
          ROUTER.push('dashboard')
        }else{
          setTimeout(() => {
            window.location.href = url
          }, 100)
        }
      }, (response, status) => {
        this.errorMessage = (status === 401) ? 'Your Username and password didnot matched.' : 'Cannot log in? Contact us through email: support@talkfluent.com'
      })
    },
    setPayPalAgreement(){
      this.payment.payment_method = this.paymentMethod
      let config = {
        'paypal': OPKEYS.paypal
      }
      let parameter = {
        'config': config,
        'account': this.account,
        'billing': this.billing,
        'payment': this.payment,
        'host': this.config.HOST,
        'paypal': this.paypal
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('accounts/create', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.error.status === 100){
          let message = response.error.message
          if(typeof message.username !== undefined && typeof message.username !== 'undefined'){
            this.errorMessage = message.username[0]
          }else if(typeof message.email !== undefined && typeof message.email !== 'undefined'){
            this.errorMessage = message.email[0]
          }else if(typeof message.card !== undefined && typeof message.card !== 'undefined'){
            this.errorMessage = message.card
          }
        }else if(response.data !== null){
          AUTH.tokenData.loading = true
          this.login(response.paypal_redirect)
        }
      })
    },
    checkCoupon(){
      if(this.payment.coupon !== '' && this.payment.coupon !== null){
        let parameter = {
          condition: [{
            value: this.payment.coupon,
            clause: '=',
            column: 'code'
          }],
          payment_keys: OPKEYS
        }
        $('#loading').css({'display': 'block'})
        this.APIRequest('coupons/retrieve', parameter).then(response => {
          $('#loading').css({'display': 'none'})
          if(response.data !== null){
            this.errorMessage = null
            this.successMessage = response.data[0].description
          }else{
            this.successMessage = null
            this.errorMessage = response.error
          }
        })
      }else{
        this.errorMessage = null
        this.successMessage = ''
      }
    },
    resetCoupon(){
      this.errorMessage = null
      this.payment.coupon = null
      $('#couponModal').modal('hide')
    },
    deleteCoupon(){
      this.errorMessage = null
      this.payment.coupon = null
      this.successMessage = null
    }
  }
}
</script>
