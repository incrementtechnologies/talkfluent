<template>
	<div class="holder">
    <b>
      {{
        (paymentMethod.method === 'stripe' && user.paymentStatus === 'failed') ? 'Choose your plan' : 'You may upgrade your plan'
      }}</b>
    <br>

    <div class="pricing-item next-pricing" v-if="user.plan === 'pause'">
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
            PAUSED
          </span>
        </span>
        <span class="lead">
          <span class="text">SUBSCRIPTION PLANS THAT WORK FOR EVERYONE</span>
          <span class="coupon-message" v-if="(couponErrorMessageMonthly !== null || couponSuccessMessageMonthly !== null) && paymentMethod.method === 'stripe'">
            <label class="text-success" v-if="couponSuccessMessageMonthly !== null"><b>{{couponSuccessMessageMonthly}}</b></label>
            <label class="text-danger" v-if="couponErrorMessageMonthly !== null"><b>{{couponErrorMessageMonthly}}</b></label>
          </span>
          <div class="coupons" v-if="paymentMethod.method === 'stripe' && (user.plan !== 'monthly' || user.paymentStatus === 'failed')">
            <input type="text" class="form-control" placeholder="Enter Coupon Code" v-model="newCouponMonthly" :disabled="couponSuccessMessageMonthly !== null">
            <div class="button-holder">
              <button class="btn btn-primary pull-right" v-on:click="retrieveCoupon('monthly')" v-if="couponSuccessMessageMonthly === null">APPLY</button>
              <button class="btn btn-danger pull-right" v-on:click="deleteMonthlyCoupon('monthly')" v-if="couponSuccessMessageMonthly !== null">DELETE</button>
            </div>
          </div>
          <button class="btn btn-success btn-whole" v-if="user.plan === 'monthly' && user.paymentStatus !== 'failed'">
            YOUR CURRENT ACTIVE PLAN
          </button>
          <div class="row" style="text-align: center; width: 99%; margin-left: 0px;">
            <div class="columns">
              <button v-on:click="cancelPlan()" class="btn btn-danger btn-whole" style="height: 45px; width: 78%; float: right; margin-right: 5px; text-align: center;" v-if="user.plan === 'monthly' && user.paymentStatus !== 'failed'">
                CANCEL PLAN
              </button>
            </div>
            <div class="columns">
              <button v-on:click="pausePlan()" class="btn btn-primary btn-whole" style="height: 45px; width: 72%; float: left; padding: 5px;" v-if="user.plan === 'monthly' && user.paymentStatus !== 'failed'">
                PAUSE PLAN
              </button>
            </div>
          </div>
          <button class="btn btn-primary btn-whole" v-if="user.plan !== 'monthly' && paymentMethod.method === 'stripe' && user.paymentStatus !== 'failed'" v-on:click="upgradePlanStripe('monthly', paymentMethod.source)">
            BUY THIS PLAN
          </button>
          <button class="btn btn-primary btn-whole" v-if="user.plan !== 'monthly' && paymentMethod.method === 'paypal' && user.paymentStatus !== 'failed'" v-on:click="upgradePlanPaypal('monthly')">
            BUY THIS PLAN
          </button>
          <button class="btn btn-primary btn-whole" v-if="paymentMethod.method === 'stripe' && user.paymentStatus === 'failed'" v-on:click="addPlanStripe('monthly')">
            BUY THIS PLAN
          </button>
          <button class="btn btn-primary btn-whole" v-if="paymentMethod.method === 'paypal' && user.paymentStatus === 'failed'" v-on:click="addPlanPaypal('monthly')">
            BUY THIS PLAN
          </button>
          <span class="cancel" v-if="user.status !== 'trial' && user.plan === 'monthly' && user.paymentStatus !== 'failed' && user.canceledOn === null">
            <a @click="cancellation(paymentMethod.method)">Cancel your plan</a>
          </span>
          <span class="ended-plan" v-if="user.status !== 'trial' && user.plan === 'monthly' && user.paymentStatus !== 'failed' && user.canceledOn !== null">
            Your plan will end on {{user.canceledOn}}
          </span>
          
        </span>
      </span>
    </div>

    <div class="pricing-item next-pricing">
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
          <span class="coupon-message" v-if="(couponErrorMessageMonthly !== null || couponSuccessMessageMonthly !== null) && paymentMethod.method === 'stripe'">
            <label class="text-success" v-if="couponSuccessMessageMonthly !== null"><b>{{couponSuccessMessageMonthly}}</b></label>
            <label class="text-danger" v-if="couponErrorMessageMonthly !== null"><b>{{couponErrorMessageMonthly}}</b></label>
          </span>
          <div class="coupons" v-if="paymentMethod.method === 'stripe' && (user.plan !== 'monthly' || user.paymentStatus === 'failed')">
            <input type="text" class="form-control" placeholder="Enter Coupon Code" v-model="newCouponMonthly" :disabled="couponSuccessMessageMonthly !== null">
            <div class="button-holder">
              <button class="btn btn-primary pull-right" v-on:click="retrieveCoupon('monthly')" v-if="couponSuccessMessageMonthly === null">APPLY</button>
              <button class="btn btn-danger pull-right" v-on:click="deleteMonthlyCoupon('monthly')" v-if="couponSuccessMessageMonthly !== null">DELETE</button>
            </div>
          </div>
          <button class="btn btn-success btn-whole" v-if="user.plan === 'monthly' && user.paymentStatus !== 'failed'">
            YOUR CURRENT ACTIVE PLAN
          </button>
          <div class="row" style="text-align: center; width: 99%; margin-left: 0px;">
            <div class="columns">
              <button v-on:click="cancelPlan()" class="btn btn-danger btn-whole" style="height: 45px; width: 78%; float: right; margin-right: 5px; text-align: center;" v-if="user.plan === 'monthly' && user.paymentStatus !== 'failed'">
                CANCEL PLAN
              </button>
            </div>
            <div class="columns">
              <button v-on:click="pausePlan()" class="btn btn-primary btn-whole" style="height: 45px; width: 72%; float: left; padding: 5px;" v-if="user.plan === 'monthly' && user.paymentStatus !== 'failed'">
                PAUSE PLAN
              </button>
            </div>
          </div>
          <button class="btn btn-primary btn-whole" v-if="user.plan !== 'monthly' && paymentMethod.method === 'stripe' && user.paymentStatus !== 'failed'" v-on:click="upgradePlanStripe('monthly', paymentMethod.source)">
            BUY THIS PLAN
          </button>
          <button class="btn btn-primary btn-whole" v-if="user.plan !== 'monthly' && paymentMethod.method === 'paypal' && user.paymentStatus !== 'failed'" v-on:click="upgradePlanPaypal('monthly')">
            BUY THIS PLAN
          </button>
          <button class="btn btn-primary btn-whole" v-if="paymentMethod.method === 'stripe' && user.paymentStatus === 'failed'" v-on:click="addPlanStripe('monthly')">
            BUY THIS PLAN
          </button>
          <button class="btn btn-primary btn-whole" v-if="paymentMethod.method === 'paypal' && user.paymentStatus === 'failed'" v-on:click="addPlanPaypal('monthly')">
            BUY THIS PLAN
          </button>
          <span class="cancel" v-if="user.status !== 'trial' && user.plan === 'monthly' && user.paymentStatus !== 'failed' && user.canceledOn === null">
            <a @click="cancellation(paymentMethod.method)">Cancel your plan</a>
          </span>
          <span class="ended-plan" v-if="user.status !== 'trial' && user.plan === 'monthly' && user.paymentStatus !== 'failed' && user.canceledOn !== null">
            Your plan will end on {{user.canceledOn}}
          </span>
          
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
          <span class="coupon-message" v-if="(couponErrorMessageAnnually !== null || couponSuccessMessageAnnually !== null) && paymentMethod.method === 'stripe'">
            <label class="text-success" v-if="couponSuccessMessageAnnually !== null"><b>{{couponSuccessMessageAnnually}}</b></label>
            <label class="text-danger" v-if="couponErrorMessageAnnually !== null"><b>{{couponErrorMessageAnnually}}</b></label>
          </span>
          <div class="coupons" v-if="paymentMethod.method === 'stripe' && (user.plan !== 'annually' || user.paymentStatus === 'failed')">
            <input type="text" class="form-control" placeholder="Enter Coupon Code" v-model="newCouponAnnually" :disabled="couponSuccessMessageAnnually !== null">
            <div class="button-holder">
              <button class="btn btn-primary pull-right" v-on:click="retrieveCoupon('annually')" v-if="couponSuccessMessageAnnually === null">APPLY</button>
              <button class="btn btn-danger pull-right" v-on:click="deleteAnnuallyCoupon('monthly')" v-if="couponSuccessMessageAnnually !== null">DELETE</button>
            </div>
          </div>
          <button class="btn btn-danger btn-whole" v-if="user.plan === 'annually' && user.paymentStatus !== 'failed'">
            YOUR CURRENT ACTIVE PLAN
          </button>
          <div class="row" style="text-align: center; width: 99%; margin-left: 0px;">
            <div class="columns">
              <button v-on:click="cancelPlan()" class="btn btn-danger btn-whole" style="height: 45px; width: 78%; float: right; margin-right: 5px; text-align: center;" v-if="user.plan === 'annually' && user.paymentStatus !== 'failed'">
                CANCEL PLAN
              </button>
            </div>
            <div class="columns">
              <button v-on:click="pausePlan()" class="btn btn-primary btn-whole" style="height: 45px; width: 72%; float: left; padding: 5px;" v-if="user.plan === 'annually' && user.paymentStatus !== 'failed'">
                PAUSE PLAN
              </button>
            </div>
          </div>
          <button class="btn btn-primary btn-whole" v-if="user.plan !== 'annually' && paymentMethod.method === 'stripe' && user.paymentStatus !== 'failed'" v-on:click="upgradePlanStripe('annually')">
            BUY THIS PLAN
          </button>
          <button class="btn btn-primary btn-whole" v-if="user.plan !== 'annually' && paymentMethod.method === 'paypal' && user.paymentStatus !== 'failed'" v-on:click="upgradePlanPaypal('annually')">
            BUY THIS PLAN
          </button>
          <button class="btn btn-primary btn-whole" v-if="paymentMethod.method === 'stripe' && user.paymentStatus === 'failed'" v-on:click="addPlanStripe('annually')">
            BUY THIS PLAN
          </button>
          <button class="btn btn-primary btn-whole" v-if="paymentMethod.method === 'paypal' && user.paymentStatus === 'failed'" v-on:click="addPlanPaypal('annually')">
           BUY THIS PLAN
          </button>
           <span class="cancel" v-if="user.status !== 'trial' && user.plan === 'annually' && user.paymentStatus !== 'failed' && user.canceledOn === null">
            <a @click="cancellation(paymentMethod.method)">Cancel your plan</a>
          </span>
          <span class="ended-plan" v-if="user.status !== 'trial' && user.plan === 'annually' && user.paymentStatus !== 'failed' && user.canceledOn !== null">
            Your plan will end on {{user.canceledOn}}
          </span>
        </span>
      </span>
    </span>


    <span class="pricing-item next-pricing" v-if="user.enabledLesson === true">
      <span class="header-item bg-green">
        <label>PAUSE ACCOUNT</label>
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
          <span class="coupon-message" v-if="(couponErrorMessagePause !== null || couponSuccessMessagePause !== null) && paymentMethod.method === 'stripe'">
            <label class="text-success" v-if="couponSuccessMessagePause !== null"><b>{{couponSuccessMessagePause}}</b></label>
            <label class="text-danger" v-if="couponErrorMessagePause !== null"><b>{{couponErrorMessagePause}}</b></label>
          </span>
          <div class="coupons" v-if="paymentMethod.method === 'stripe' && (user.plan !== 'pause' || user.paymentStatus === 'failed')">
            <input type="text" class="form-control" placeholder="Enter Coupon Code" v-model="newCouponPause" :disabled="couponSuccessMessagePause !== null">
            <div class="button-holder">
              <button class="btn btn-primary pull-right" v-on:click="retrieveCoupon('pause')" v-if="couponSuccessMessagePause === null">APPLY</button>
              <button class="btn btn-danger pull-right" v-on:click="deletePauseCoupon('monthly')" v-if="couponSuccessMessagePause !== null">DELETE</button>
            </div>
          </div>
          <button class="btn btn-danger btn-whole" v-if="user.plan === 'pause' && user.paymentStatus !== 'failed'">
            YOUR CURRENT ACTIVE PLAN
          </button>
          <div class="row" style="text-align: center; width: 99%; margin-left: 0px;">
            <div class="columns">
              <button v-on:click="cancelPlan()" class="btn btn-danger btn-whole" style="height: 45px; width: 78%; float: right; margin-right: 5px; text-align: center;" v-if="user.plan === 'pause' && user.paymentStatus !== 'failed'">
                CANCEL PLAN
              </button>
            </div>
            <div class="columns">
              <button v-on:click="pausePlan()" class="btn btn-primary btn-whole" style="height: 45px; width: 72%; float: left; padding: 5px;" v-if="user.plan === 'pause' && user.paymentStatus !== 'failed'">
                PAUSE PLAN
              </button>
            </div>
          </div>
          <button class="btn btn-primary btn-whole" v-if="user.plan !== 'pause' && paymentMethod.method === 'stripe' && user.paymentStatus !== 'failed'" v-on:click="upgradePlanStripe('pause')">
            BUY THIS PLAN
          </button>
          <button class="btn btn-primary btn-whole" v-if="user.plan !== 'pause' && paymentMethod.method === 'paypal' && user.paymentStatus !== 'failed'" v-on:click="upgradePlanPaypal('pause')">
            BUY THIS PLAN
          </button>
          <button class="btn btn-primary btn-whole" v-if="paymentMethod.method === 'stripe' && user.paymentStatus === 'failed'" v-on:click="addPlanStripe('pause')">
            BUY THIS PLAN
          </button>
          <button class="btn btn-primary btn-whole" v-if="paymentMethod.method === 'paypal' && user.paymentStatus === 'failed'" v-on:click="addPlanPaypal('pause')">
            BUY THIS PLAN
          </button>
           <span class="cancel" v-if="user.status !== 'trial' && user.plan === 'pause' && user.paymentStatus !== 'failed' && user.canceledOn === null">
            <a @click="cancellation(paymentMethod.method)">Cancel your plan</a>
          </span>
          <span class="ended-plan" v-if="user.status !== 'trial' && user.plan === 'pause' && user.paymentStatus !== 'failed' && user.canceledOn !== null">
            Your plan will end on {{user.canceledOn}}
          </span>
          <span class="ended-plan" v-if="user.pausedFlag === true && user.pausedOn !== null">
            Your paused plan will effect on {{user.pausedOn}}
          </span>
        </span>
      </span>
    </span>

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

    <cancel-plan :paymentMethod="paymentMethod"></cancel-plan>

	</div>
</template>
<style scoped>
.columns {
  float: left;
  width: 50%;
}
.holder{
	width: 100%;
	float: left;
	margin-bottom: 50px;
}

.pricing-item{
  width: 30%;
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
.lead .cancel, .lead .ended-plan{
  text-align: center;
  width: 100%;
  float: left;
  line-height: 12px;
  font-style: italic;
  font-size: 12px !important;
  margin-bottom: 16px;
}
.lead .cancel:hover{
  cursor: pointer;
  text-decoration: underline;
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
  margin-left: 1%;
  margin-right: 1%;
}
@media (max-width: 992px){
  .pricing-item{
    width: 90%;
    margin-left: 5% !important;
    margin-right: 5% !important;
    margin-bottom: 0px;
  }
  .lead .coupons{
    height: 120px !important;
  }
  .lead .coupons input{
    width: 100% !important;
  }
  .lead .coupons .button-holder{
    width: 100% !important;
  }
  .lead .coupons .button-holder button{
    margin-top: 5px;
    width: 100% !important;
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
  mounted(){
    console.log(this.user, 'hiiii')
  },
  data(){
    return{
      user: AUTH.user,
      config: CONFIG,
      products: PRODUCTS,
      stripeSK: (OPKEYS.flag === false) ? OPKEYS.stripe.dev_pk : OPKEYS.stripe.live_pk,
      options: {
      },
      errorMessage: null,
      newCouponMonthly: null,
      newCouponAnnually: null,
      newCouponPause: null,
      couponDetails: null,
      couponErrorMessageMonthly: null,
      couponErrorMessageAnnually: null,
      couponErrorMessagePause: null,
      couponSuccessMessageMonthly: null,
      couponSuccessMessageAnnually: null,
      couponSuccessMessagePause: null
    }
  },
  props: ['paymentMethod', 'creditCard'],
  components: {
    'cancel-plan': require('modules/billing/CancelPlan.vue')
  },
  methods: {
    cancelPlan() {
      $('#requestToCancel').modal('show')
    },
    pausePlan() {
      this.addPlanStripe('pause')
    },
    redirect(route){
      ROUTER.push(route)
    },
    retrieve(){
      this.$parent.retrieveBilling()
    },
    upgradePlanStripe(plan){
      if(this.couponErrorMessageMonthly === null && this.couponErrorMessageAnnually === null && this.couponErrorMessagePause === null){
        let parameter = {
          payment_keys: OPKEYS,
          products: PRODUCTS,
          account_id: this.user.userID,
          plan: plan,
          credit_card_id: this.creditCard[0].id,
          coupon: this.getCoupon(plan)
        }
        $('#loading').css({'display': 'block'})
        this.APIRequest('stripes/upgrade', parameter).then(response => {
          $('#loading').css({'display': 'none'})
          if(response.data !== null){
            AUTH.checkAuthentication(null)
            this.newCoupon = null
            this.setCouponToNull()
            this.$parent.retrieveBilling()
          }else if(response.data === null){
            this.$parent.notAllowedMessage = response.error
            $('#notAllowedUpgrade').modal('show')
          }
        })
      }else{
        $('#couponModal').modal('show')
      }
    },
    upgradePlanPaypal(plan){
      let config = {
        'paypal': OPKEYS.paypal
      }
      let parameter = {
        account_id: this.user.userID,
        'config': config,
        payment_status: this.user.paymentStatus,
        plan: plan
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('paypal/upgrade', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.data !== null){
          window.location.href = response.data
        }else{
          this.$parent.notAllowedMessage = response.error
          $('#notAllowedUpgrade').modal('show')
        }
      })
    },
    addPlanStripe(plan){
      if(this.creditCard !== null && this.creditCard[0].status === 'active' && this.couponErrorMessageMonthly === null && this.couponErrorMessageAnnually === null && this.couponErrorMessagePause === null){
        let parameter = {
          payment_keys: OPKEYS,
          customer_id: this.creditCard[0].customer,
          plan: plan,
          products: PRODUCTS,
          credit_card_id: this.creditCard[0].id,
          account_id: this.user.userID,
          coupon: this.getCoupon(plan)
        }
        // call api
        $('#loading').css({'display': 'block'})
        this.APIRequest('stripes/new_subscription', parameter).then(response => {
          $('#loading').css({'display': 'none'})
          if(response.data !== null){
            AUTH.checkAuthentication(null)
            this.setCouponToNull()
            this.$parent.retrieveBilling()
          }else{
            this.errorMessage = response.error
          }
        })
      }else{
        $('#paymentMethod').modal('show')
      }
    },
    retrieveCoupon(plan){
      let newCoupon = this.getCoupon(plan)
      if(newCoupon !== null && newCoupon !== ''){
        let parameter = {
          condition: [{
            value: newCoupon,
            column: 'code',
            clase: '='
          }],
          payment_keys: OPKEYS
        }
        $('#loading').css({'display': 'block'})
        this.APIRequest('coupons/retrieve', parameter).then(response => {
          $('#loading').css({'display': 'none'})
          if(response.data !== null){
            this.couponDetails = response.data
            this.couponErrorMessageMonthly = null
            this.couponErrorMessageAnnually = null
            this.couponErrorMessagePause = null
            if(plan === 'monthly'){
              this.couponSuccessMessageMonthly = response.data[0].description
            }else if(plan === 'annually'){
              this.couponSuccessMessageAnnually = response.data[0].description
            }else{
              this.couponSuccessMessagePause = response.data[0].description
            }
          }else{
            this.couponDetails = null
            this.couponSuccessMessageMonthly = null
            this.couponSuccessMessagePause = null
            this.couponSuccessMessageAnnually = null
            if(plan === 'monthly'){
              this.couponErrorMessageMonthly = response.error
            }else if(plan === 'annually'){
              this.couponErrorMessageAnnually = response.error
            }else{
              this.couponErrorMessagePause = response.error
            }
          }
        })
      }else{
        this.couponSuccessMessageMonthly = null
        this.couponSuccessMessagePause = null
        this.couponSuccessMessageAnnually = null
        if(plan === 'monthly'){
          this.couponErrorMessageMonthly = 'Please fill in the coupon field'
        }else if(plan === 'annually'){
          this.couponErrorMessageAnnually = 'Please fill in the coupon field'
        }else{
          this.couponErrorMessagePause = 'Please fill in the coupon field'
        }
      }
    },
    resetCoupon(){
      $('#couponModal').modal('hide')
      this.setCouponToNull()
    },
    setCouponToNull(){
      this.newCouponMonthly = null
      this.newCouponAnnually = null
      this.newCouponPause = null
      this.couponErrorMessageMonthly = null
      this.couponErrorMessageAnnually = null
      this.couponErrorMessagePause = null
      this.couponSuccessMessageMonthly = null
      this.couponSuccessMessageAnnually = null
      this.couponSuccessMessagePause = null
    },
    getCoupon(plan){
      switch(plan){
        case 'monthly': return this.newCouponMonthly
        case 'annually': return this.newCouponAnnually
        case 'pause': return this.newCouponPause
      }
    },
    cancellation(paymentMethod){
      for(let i = 0; i < this.$children.length; i++){
        if(this.$children[i].$el.id === 'cancellationPlan'){
          this.$children[i].paymentMethod = paymentMethod
          this.$children[i].check()
        }
      }
    },
    deleteMonthlyCoupon(){
      this.couponErrorMessageMonthly = null
      this.couponSuccessMessageMonthly = null
      this.newCouponMonthly = null
    },
    deleteAnnuallyCoupon(){
      this.couponErrorMessageAnnually = null
      this.couponSuccessMessageAnnually = null
      this.newCouponAnnually = null
    },
    deletePauseCoupon(){
      this.couponErrorMessagePause = null
      this.couponSuccessMessagePause = null
      this.newCouponPause = null
    }
  }
}
</script>
