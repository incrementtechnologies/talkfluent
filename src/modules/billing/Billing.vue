<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" v-on:click="redirect('/dashboard')"><a href="#" onclick="return false;">Dashboard</a></li>
          <li class="breadcrumb-item active">My Plan</li>
        </ul>
      </div>
      <div class="col-lg-10 mx-auto" style="margin-top: 25px;">
        <new-payment-method :paymentMethod="paymentMethod" ref='addNew' :creditCard="creditCard" :paypal="paypal"></new-payment-method>
      </div>
      <!-- border: 1px solid #c7c7c7;  -->
      <div class="col-lg-10 mx-auto">
        <div style="cursor: pointer; padding: 10px; width: 66%" v-on:click="addPaymentMethod()"> 
          Add new payment method<i class="fas fa-chevron-right chevron" style="margin-left: 50px;"></i>
        </div>
      </div>
      <!-- border: 1px solid #c7c7c7;  -->
      <div class="col-lg-10 mx-auto" v-if="otherPayments">
        <div class="login-fields mt-3 col-lg-7">
            <div class="radio">
              <div>
                <div class="row">
                  <div class="form-group login-spacer col-lg-12 col-md-12 col-sm-12">
                    <p v-if="errorMessage !== null" style="color:red;">{{errorMessage}}</p>
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
              </div>
            </div>
            <div class="radio">
            </div>
            <h6>
              <input type="checkbox" v-model="billingTerms" name="terms"/>
              I Agree to the <a v-bind:href="config.WEBSITE + '/billing_terms'" target="_BLANK">Billing Terms</a>
            </h6>
            <button class="btn btn-primary btn-whole mt-2" style="width: 50%; height: 50px;" v-on:click="validatePaymentStripe()">
              ADD PAYMENT METHOD
            </button>
          </div>
      </div>
      <div class="col-lg-10 mx-auto" v-if="otherPayments">
      </div>
      <div class="col-lg-10 mx-auto" style="margin-top: 25px;" v-if="paymentMethod !== null && (paypal !== null || creditCard !== null) && this.$refs.addNew.newPaymentMethod !== 'paypal'">
        <upgrade-plans :paymentMethod="paymentMethod" :creditCard="creditCard"></upgrade-plans>
      </div>
      <div class="col-lg-10 mx-auto" style="margin-top: 25px;">
        <b>Plan and Payment History</b>
        <br>
        <table class="table table-bordered table-stripped table-responsive">
          <thead>
            <td>Date</td>
            <td>Status</td>
            <td>Payment Method</td>
            <td>Description</td>
            <td>Amount</td>
            <td>Discounts</td>
            <td>Total Amount</td>
          </thead>
          <tbody>
            <tr v-for="item, index in billingHistory">
              <td>{{item.start_date_human + ' - ' + item.end_date_human}}</td>
              <td>{{item.status}}</td>
              <td>
                {{(item.payment_method === 'credit_card') ? 'Credit Card' : 'PayPal'}}
                <br>
                <label v-if="item.stripe !== null"><i  v-bind:class="item.stripe.brand_icon" class="fab" style="font-size: 32px;"></i></label>
                <label v-if="item.stripe !== null">{{'********' + item.stripe.last4}}</label>
              </td>
              <td>{{item.description}}</td>
              <td>{{item.description_amount}}</td>
              <td>
                  {{parseFloat(item.discount_total_amount).toFixed(2)}}
                  <label v-if="item.coupon !== null">{{' - ' + item.coupon.description}}</label>
              </td>
              <td><b>{{item.currency.toUpperCase()}} {{parseFloat(parseFloat(item.total_amount) / 100).toFixed(2)}}</b></td>
            </tr>
          </tbody>
        </table>
      </div>

    

    <div class="modal fade" id="notAllowedUpgrade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">Error Message</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container" style="margin-top: 10px;margin-bottom: 10px;">
              {{notAllowedMessage}}
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
          </div>
        </div>
      </div>
    </div>



  <!-- Modals -->

      <div class="modal fade" id="paymentMethod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header bg-primary">
              <h5 class="modal-title" id="exampleModalLabel">Error Message</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="text-white">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container" style="margin-top: 10px;">Please make sure your payment method is active. If payment method is failed please add new payment method.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
            </div>
          </div>
        </div>
      </div>

    
  </div>
</template>
<style scoped>

.customModal{
  position: fixed;
  background-color: rgba(0, 0, 0, 1);
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  z-index: 100000 !important;
  opacity: 0.5;
  pointer-events: none;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  transition: all 0.3s;
  text-align: center;
  display: none;
}
.customModal .loading{
  font-size: 50px;
  margin-top: 200px;
  height: 50px;
  float: left;
  width: 100%;
  text-align: center;
  color: #00bff3;
}

.table{
  margin-top: 25px !important;
  margin-bottom: 25px !important;
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
import CardExpiry from '../../components/stripe/CardExpiry'
import CardCvc from '../../components/stripe/CardCvc'
import CardNumber from '../../components/stripe/CardNumber'
import { Stripe } from '../../components/stripe/stripeElements'
export default {
  created(){
    AUTH.redirectToLogin()
    this.retrieveBilling()
    this.retrieveAccountInformation()
  },
  data(){
    return {
      stripeSK: (OPKEYS.flag === false) ? OPKEYS.stripe.dev_pk : OPKEYS.stripe.live_pk,
      user: AUTH.user,
      config: CONFIG,
      tokenData: AUTH.tokenData,
      products: PRODUCTS,
      accountsItem: [
        {title: 'Test', flag: true},
        {title: 'Test', flag: false},
        {title: 'Test', flag: false},
        {title: 'Test', flag: false}
      ],
      billingHistory: null,
      creditCard: null,
      paymentMethod: null,
      paypal: null,
      notAllowedMessage: null,
      otherPayments: false,
      selectedMethod: null,
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
      number: false,
      expiry: false,
      billingTerms: null,
      options: {
        style: {base: {
          fontSize: '16px'
        }}
      },
      errorMessage: null,
      last_name: null,
      first_name: null
    }
  },
  components: {
    'new-payment-method': require('modules/billing/NewPaymentMethod.vue'),
    'upgrade-plans': require('modules/billing/UpgradePlans.vue'),
    CardNumber,
    CardCvc,
    CardExpiry
  },
  methods: {
    retrieveAccountInformation(){
      let parameter = {
        condition: [{
          value: this.user.userID,
          column: 'account_id',
          clause: '='
        }]
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('account_informations/retrieve', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.data.length > 0){
          this.last_name = response.data[0].last_name
          this.first_name = response.data[0].first_name
        }
      })
    },
    addMethod(source) {
      let account = this.user
      account['first_name'] = this.first_name
      account['last_name'] = this.last_name
      let parameter = {
        account: account,
        source: source,
        payment_keys: OPKEYS
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('credit_cards/create', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        this.retrieveBilling()
      })
    },
    validatePaymentStripe(){
      if(this.number === false || this.expiry === false) {
        this.errorMessage = 'Invalid input.'
        return false
      } else if(this.billingTerms === false || this.billingTerms === null){
        this.errorMessage = 'Please agree the Billing Terms.'
        return false
      } else {
        Stripe.createSource().then(data => {
          this.errorMessage = null
          if(data.error !== undefined){
            this.errorMessage = data.error.message
            return false
          }else{
            this.addMethod(data.source)
            return true
          }
        })
      }
    },
    addPaymentMethod() {
      // this.otherPayments = !this.otherPayments
      this.$refs.addNew.addPaymentMethodFlag = !this.$refs.addNew.addPaymentMethodFlag
    },
    executeAgreement(){
      if(this.user.userID === 0){
        window.location.href = window.location.href
      }
      let config = {
        'paypal': OPKEYS.paypal
      }
      let parameter = {
        'paypal_token': this.token,
        'config': config,
        'account_id': this.user.userID
      }
      this.APIRequest('paypal/execute_agreement', parameter).then(response => {
        // console.log(response)
      })
    },
    redirect(parameter){
      ROUTER.push(parameter)
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
        this.billingHistory = response.billing
        this.paymentMethod = response.payment_method
        this.paypal = response.paypal
        this.creditCard = response.credit_card
      })
    }
  }
}
</script>
