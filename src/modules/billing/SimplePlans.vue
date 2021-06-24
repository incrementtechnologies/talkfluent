<template>
  <div class="holder">
    <b>Current subscription plan</b>
    <br>
    <div class="plan-holder" v-if="user !== null">
      <span class="plan-header" v-if="user.canceledOn === null">
        <p v-if="user.plan === 'monthly'">
          <b>Monthly Plan for $49.00</b>
        </p>
        <p v-if="user.plan === 'annually'">
          <b>Annual Plan for $228</b>
          <br />
          <p style="color: #999;">
            For only $19.00 per month
          </p>
        </p>
        <p style="color: #999;">
          Next billing is on: July 31, 2021
        </p>
      </span>
      <span class="plan-footer" v-if="user.canceledOn === null">
        <button type="button" class="btn btn-primary change-plan" @click="showPlanModal()">Change plan</button>
        <button type="button" class="btn btn-danger change-plan" @click="cancelPlan()">Cancel plan</button>
      </span>
      <span class="plan-header" v-if="user.canceledOn !== null">
        <p>
          <b class="text-danger">You don't have an existing plan</b>
        </p>
      </span>
      <span class="plan-footer" v-if="user.canceledOn !== null">
        <button type="button" class="btn btn-primary btn-block" @click="showPlanModal()">Add Plan</button>
      </span>
    </div>

    <div class="modal fade" id="simplePlan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">Available plans</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p v-if="errorMessage !== null" class="text-danger text-center">{{errorMessage}}</p>
            <div class="plan-holder plan-modal" v-if="user.plan !== 'monthly' || user.canceledOn !== null">
              <span class="plan-header">
                <p>
                  <b>Monthly Plan for $99.00</b>
                </p>
              </span>
              <span class="plan-footer" v-if="auth.user.billingHistory !== null && auth.user.billingHistory.length > 0">
                <button type="button" class="btn btn-primary" @click="switchTo('monthly')" v-if="user.canceledOn === null">Switch to monthly</button>
                <button type="button" class="btn btn-primary" @click="addPlan('monthly')" v-if="user.canceledOn !== null">Upgrade to monthly</button>
              </span>
              <span class="plan-footer" v-else>
                <button type="button" class="btn btn-primary" @click="addPlan('monthly')">Upgrade to monthly</button>
              </span>
            </div>

            <div class="plan-holder plan-modal" v-if="user.plan !== 'annually' || user.canceledOn !== null">
              <span class="plan-header">
                <p>
                  <b>Annual Plan for $228</b>
                  <br />
                  <p style="color: #999;">
                    61% OFF! YOU SAVE $360/YEAR! For only $19.00 per month
                  </p>
                </p>
              </span>
              <span class="plan-footer" v-if="auth.user.billingHistory !== null && auth.user.billingHistory.length > 0">
                <button type="button" class="btn btn-primary" @click="switchTo('annually')" v-if="user.canceledOn === null">Upgrade yearly</button>
                <button type="button" class="btn btn-primary" @click="addPlan('annually')" v-if="user.canceledOn !== null">Upgrade to yearly</button>
              </span>
              <span class="plan-footer" v-else>
                <button type="button" class="btn btn-primary" @click="addPlan('annually')">Upgrade to yearly</button>
              </span>
            </div>


          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
          </div>
        </div>
      </div>
    </div>


    <cancel-plan></cancel-plan>
  </div>
</template>
<style scoped>

.plan-holder{
  width: 32%;
  padding: 20px;
  border-radius: 5px;
  border: solid 1px #eee;
  margin-top: 25px;
  overflow-y: hidden;
  min-height: 50px;
}

.plan-modal{
  width: 100%;
  border: 0px;
  border-radius: 0px;
  overflow-y: hidden;
  min-height: 50px;
}

.plan-modal:hover{
  cursor: pointer;
  background-color: #eee;
}

.btn{
  height: 50px !important;
}

.change-plan{
  width: 49%;
  margin-right: 1%;
  float: left;
}

.modal-body{
  padding: 20px !important;
}

@media screen and (max-width: 610px){
  .plan-holder{
    width: 96%;
    margin-left: 2%;
    margin-right: 2%;
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
  },
  data(){
    return{
      user: AUTH.user,
      auth: AUTH,
      config: CONFIG,
      products: PRODUCTS,
      errorMessage: null
    }
  },
  props: ['paymentMethod', 'creditCard'],
  components: {
    'cancel-plan': require('modules/billing/CancelPlan.vue')
  },
  methods: {
    redirect(route){
      ROUTER.push(route)
    },
    showPlanModal(){
      $('#simplePlan').modal('show')
    },
    cancelPlan() {
      $('#requestToCancel').modal('show')
    },
    switchTo(plan){
      if(AUTH.user.paymentMethod !== null){
        switch(AUTH.user.paymentMethod.method){
          case 'stripe':
            this.upgradePlanStripe(plan)
            break
          case 'paypal':
            this.upgradePlanPaypal(plan)
            break
        }
      }
    },
    addPlan(plan){
      this.errorMessage = null
      if(AUTH.user.paymentMethod !== null){
        switch(AUTH.user.paymentMethod.method){
          case 'stripe':
            this.addPlanStripe(plan)
            break
          case 'paypal':
            this.upgradePlanPaypal(plan)
            break
        }
      }
    },
    upgradePlanPaypal(plan){
      if(AUTH.user.userID < 0){
        return
      }
      if(AUTH.user.paymentMethod == null || (AUTH.user.paymentMethod && AUTH.user.paymentMethod.details === null)){
        return
      }
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
          this.errorMessage = response.error
        }
      })
    },
    upgradePlanStripe(plan){
      if(AUTH.user.userID < 0){
        return
      }
      if(AUTH.user.paymentMethod == null || (AUTH.user.paymentMethod && AUTH.user.paymentMethod.details === null)){
        return
      }
      let paymentDetails = AUTH.user.paymentMethod.details
      let parameter = {
        payment_keys: OPKEYS,
        products: PRODUCTS,
        account_id: this.user.userID,
        plan: plan,
        credit_card_id: paymentDetails.id,
        coupon: null
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('stripes/upgrade', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.data !== null){
          AUTH.checkAuthentication(null)
          $('#simplePlan').modal('hide')
          this.$parent.retrieveHistory()
        }else if(response.data === null){
          this.$parent.notAllowedMessage = response.error
          $('#notAllowedUpgrade').modal('show')
        }
      })
    },
    addPlanStripe(plan){
      if(AUTH.user.userID < 0){
        return
      }
      if(AUTH.user.paymentMethod == null || (AUTH.user.paymentMethod && AUTH.user.paymentMethod.details === null)){
        return
      }
      let paymentDetails = AUTH.user.paymentMethod.details
      let parameter = {
        payment_keys: OPKEYS,
        customer_id: paymentDetails.customer,
        plan: plan,
        products: PRODUCTS,
        credit_card_id: paymentDetails.id,
        account_id: this.user.userID,
        coupon: null
      }
      // call api
      $('#loading').css({'display': 'block'})
      this.APIRequest('stripes/new_subscription', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.data !== null){
          AUTH.checkAuthentication(null)
          $('#simplePlan').modal('hide')
          this.$parent.retrieveHistory()
        }else{
          this.errorMessage = response.error
        }
      })
    }
  }
}
</script>
