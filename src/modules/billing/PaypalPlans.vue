<template>
	<div class="holder">
    <div class="pricing-item">
      <span class="header-options option-false"></span>
      <span class="header-item bg-green">
        <label>MONTHLY</label>
      </span>
      <span class="pricing-content-holder">
        <span class="cost bg-green">
          <span class="top">
            <span class="value">${{products.monthly}}</span>
            <span class="details">USD / Month</span>
          </span>
          <span class="bottom-pricing">Billed Monthly</span>
        </span>
        <span class="other-details">
          <span class="item bg-green">
            Regular
          </span>
        </span>
        <span class="lead">
          <span class="text">SUBSCRIPTION PLANS THAT WORK FOR EVERYONE</span>
            <input type="text" class="form-control" placeholder="Enter Nickname(Optional)" v-model="nicknameMonthly" style="width: 80%; margin-right: 10%; margin-left: 10%; margin-bottom: 15px;">
          <button class="btn btn-primary btn-whole" v-on:click="createPaypal('monthly')" v-if="user.plan !== 'monthly'">
            BUY THIS PLAN
          </button>
          <button class="btn btn-success btn-whole" v-on:click="createPaypal('monthly')" v-else>
            YOUR ACTIVE PLAN
          </button>
          <button v-on:click="cancelPlan()" class="btn btn-danger btn-whole"v-if="user.plan === 'monthly' && user.canceledOn === null">
            CANCEL PLAN
          </button>
<!--           <div class="row" style="text-align: center; width: 99%; margin-left: 0px;">
            <div class="columns">
              <button v-on:click="cancelPlan()" class="btn btn-danger btn-whole" style="height: 45px; width: 78%; float: right; margin-right: 5px; text-align: center;" v-if="user.plan === 'monthly' && user.canceledOn === null">
                CANCEL PLAN
              </button>
            </div>
            <div class="columns">
              <button v-on:click="pausePlan()" class="btn btn-primary btn-whole" style="height: 45px; width: 78%; float: left; padding: 5px;" v-if="user.plan === 'monthly' && user.canceledOn === null">
                PAUSE PLAN
              </button>
            </div>
          </div> -->
        </span>
      </span>
    </div>

    <span class="pricing-item next-pricing">
      <span class="header-item bg-green">
        <label>YEARLY</label>
      </span>
      <span class="pricing-content-holder">
        <span class="cost bg-green">
          <span class="top">
            <span class="value">${{products.annually}}</span>
            <span class="details">USD / Month</span>
          </span>
          <span class="bottom-pricing">Billed Annually</span>
        </span>
        <span class="other-details">
          <span class="item bg-green">
            61% OFF! YOU SAVE $360/YEAR!
          </span>
        </span>
        <span class="lead">
          <span class="text">SUBSCRIPTION PLANS THAT WORK FOR EVERYONE</span>
          <input type="text" class="form-control" placeholder="Enter Nickname(Optional)" v-model="nicknameAnnually" style="width: 80%; margin-right: 10%; margin-left: 10%; margin-bottom: 15px;">
          <button class="btn btn-primary btn-whole" v-on:click="createPaypal('annually')" v-if="user.plan !== 'annually'">
            BUY THIS PLAN
          </button>
          <button class="btn btn-success btn-whole" v-on:click="createPaypal('annually')" v-else>
            YOUR ACTIVE PLAN
          </button>
          <button v-on:click="cancelPlan()" class="btn btn-danger btn-whole" v-if="user.plan === 'annually' && user.canceledOn === null">
            CANCEL PLAN
          </button>
<!--           <div class="row" style="text-align: center; width: 99%; margin-left: 0px;">
            <div class="columns">
              <button v-on:click="cancelPlan()" class="btn btn-danger btn-whole" style="height: 45px; width: 78%; float: right; margin-right: 5px; text-align: center;" v-if="user.plan === 'annually' && user.canceledOn === null">
                CANCEL PLAN
              </button>
            </div>
            <div class="columns">
              <button v-on:click="pausePlan()" class="btn btn-primary btn-whole" style="height: 45px; width: 78%; float: left; padding: 5px;" v-if="user.plan === 'annually' && user.canceledOn === null">
                PAUSE PLAN
              </button>
            </div>
          </div> -->
        </span>
      </span>
    </span>

<!--     <span class="pricing-item next-pricing">
      <span class="header-item bg-green">
        <label>PAUSE YOUR ACCOUNT</label>
      </span>
      <span class="pricing-content-holder">
        <span class="cost bg-green">
          <span class="top">
            <span class="value">${{products.pause}}</span>
            <span class="details">USD / Month</span>
          </span>
          <span class="bottom-pricing">Billed Monthly</span>
        </span>
        <span class="other-details">
          <span class="item bg-green">
            Pause your account and retain access to all your learned lessons for  $9 a month
          </span>
        </span>
        <span class="lead">
          <span class="text">SUBSCRIPTION PLANS THAT WORK FOR EVERYONE</span>
          <input type="text" class="form-control" placeholder="Enter Nickname(Optional)" v-model="nicknamePause" style="width: 80%; margin-right: 10%; margin-left: 10%; margin-bottom: 15px;">
         <button class="btn btn-primary btn-whole" v-on:click="createPaypal('pause')" v-if="user.plan !== 'pause'">
            BUY THIS PLAN
          </button>
          <button class="btn btn-danger btn-whole" v-on:click="createPaypal('pause')" v-else>
            YOUR ACTIVE PLAN
          </button>
          <button v-on:click="cancelPlan()" class="btn btn-danger btn-whole" style="height: 45px; width: 78%; float: right; margin-right: 5px; text-align: center;" v-if="user.plan === 'pause' && user.canceledOn === null">
            CANCEL PLAN
          </button> -->
<!--           <div class="row" style="text-align: center; width: 99%; margin-left: 0px;">
            <div class="columns">
              <button v-on:click="cancelPlan()" class="btn btn-danger btn-whole" style="height: 45px; width: 78%; float: right; margin-right: 5px; text-align: center;" v-if="user.plan === 'pause' && user.canceledOn === null">
                CANCEL PLAN
              </button>
            </div>
            <div class="columns">
              <button v-on:click="pausePlan()" class="btn btn-primary btn-whole" style="height: 45px; width: 78%; float: left; padding: 5px;" v-if="user.plan === 'pause' && user.canceledOn === null">
                PAUSE PLAN
              </button>
            </div>
          </div> -->
<!--         </span>
      </span>
    </span> -->
    <cancel-plan :paymentMethod="paymentMethod"></cancel-plan>
	</div>
</template>
<style scoped>
.holder{
	width: 100%;
	float: left;
	margin-bottom: 50px;
}

.columns {
  float: left;
  width: 50%;
}
.pricing-item{
  width: 32%;
  float: left;
  min-height: 500px;
  overflow-y: hidden;
  margin-top: 50px;
}

.pricing-item .header-item{
  border-top-right-radius: 5px;
  border-top-left-radius: 5px;
  text-align: center;
  color: #fff;
  border: solid 1px inherit;
  float: left;
  width: 100%;
}
.pricing-item .header-item label{
  font-size: 24px;
  padding-top: 10px;
  padding-bottom: 5px;
}
.pricing-content-holder{
  width: 100%;
  float: left;
  min-height: 200px;
  overflow-y: hidden;
  border-left: solid 1px #39b54a;
  border-right: solid 1px #39b54a;
  border-bottom: solid 1px #39b54a;
}

.pricing-content-holder .cost{
  width: 100%;
  float: left;
  height: 200px;
  text-align: right;
  color: #fff;
}
.pricing-content-holder .center{
  height: 250px;
}
.cost .top{
  width: 100%;
  float: left;
  margin-top: 25px;
  text-align: center;
}
.cost .bottom-pricing {
  width: 100%;
  float: left;
  text-align: center;
  font-size: 18px;
}
.cost .top .value{
  font-size: 80px;
  font-weight: 600;
  float: left;
  width: 100%;
}

.cost .top .details{
  font-weight: 600;
  font-size: 16px;
  width: 100%;
}
.pricing-content-holder .other-details{
  width: 100%;
  float: left;
  background: #39b54a;
}
.other-details .item{
  width: 100%;
  float: left;
  text-align: center;
  font-size: 24px;
  margin-top: 25px;
  margin-bottom: 25px;
  text-transform: uppercase;
  color: #fff;
  height: 150px;
}
.pricing-content-holder .lead{
  width: 100%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
}
.pricing-content-holder .lead .coupons{
  float: left;
  width: 80%;
  height: 50px;
  margin-left: 10%;
  margin-right: 10%;
  margin-bottom: 10px;
}
.pricing-content-holder .lead .coupon-message{
  width: 100%;
  float: left;
  text-align: center;
  margin-top: 10px;
  padding-bottom: 10px;
}
.lead .coupons input{
  width: 70% !important;
  float: left;
  height: 50px !important;
}
.lead .coupons .button-holder{
  width: 30% !important;
  float: left;
}
.lead .coupons .button-holder button{
  height: 50px !important;
  width: 90% !important;
}
.pricing-content-holder .lead .text{
  width: 100%;
  margin-top: 20px;
  text-align: center;
  float: left;
  font-size: 14px;
  margin-bottom: 25px;
}
.pricing-content-holder .lead .btn-whole{
  width: 80%;
  text-align: center;
  float: left;
  margin: 0px 10% 25px 10%;
  height: 50px !important;
}

.bg-green{
  background: #39b54a;
}
.next-pricing{
  margin-left: 2%;
}
@media (max-width: 992px){
  .pricing-item{
    width: 90%;
    margin-left: 5% !important;
    margin-right: 5% !important;
    margin-bottom: 0px;
  }
  .next-pricing{
    margin-left: 0%;
  }
}
</style>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
import {Howl, Howler} from 'howler'
import OPKEYS from '../../payment.js'
import PRODUCTS from '../../products.js'
export default {
  data(){
    return{
      user: AUTH.user,
      config: CONFIG,
      products: PRODUCTS,
      nicknameAnnually: null,
      nicknameMonthly: null,
      nicknamePause: null,
      paymentMethod: null,
      creditCard: null
    }
  },
  components: {
    'cancel-plan': require('modules/billing/CancelPlan.vue')
  },
  mounted() {
    this.retrieveBilling()
  },
  methods: {
    addPlanStripe(plan){
      let parameter = {
        payment_keys: OPKEYS,
        customer_id: this.creditCard[0].customer,
        plan: plan,
        products: PRODUCTS,
        credit_card_id: this.creditCard[0].id,
        account_id: this.user.userID,
        coupon: null
      }
      // call api
      $('#loading').css({'display': 'block'})
      this.APIRequest('stripes/new_subscription', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.data !== null){
          AUTH.checkAuthentication(null)
          this.retrieveBilling()
        }else{
          this.errorMessage = response.error
        }
      })
    },
    retrieveBilling(){
      let parameter = {
        'condition': [{
          column: 'account_id',
          value: this.user.userID,
          clause: '='
        }]
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('billings/retrieve', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        this.paymentMethod = response.payment_method
        this.creditCard = response.credit_card
      })
    },
    redirect(route){
      ROUTER.push(route)
    },
    cancelPlan() {
      $('#requestToCancel').modal('show')
    },
    pausePlan() {
      this.addPlanStripe('pause')
    },
    createPaypal(plan){
      let nickname = null
      switch(plan){
        case 'annually': nickname = this.nicknameAnnually
          break
        case 'monthly': nickname = this.nicknameMonthly
          break
        case 'pause': nickname = this.nicknamePause
          break
      }
      let paypal = {
        'paypal': OPKEYS.paypal
      }
      let parameter = {
        account_id: this.user.userID,
        plan: plan,
        config: paypal,
        nickname: nickname
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('paypal/create_on_billing', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.return_url !== null){
          window.location.href = response.return_url
        }
      })
    }
  }
}
</script>
