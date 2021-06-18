<template>
  <div class="col-lg-10 mx-auto" style="margin-top: 25px;">
    <b>Invoices and payment history</b>
    <br>
    <table class="table table-bordered table-stripped table-responsive">
      <thead>
        <td>Date</td>
        <td>Status</td>
        <td>Payment Method</td>
        <td>Description</td>
        <td>Amount</td>
        <td>Discounts</td>
        <td>Toal Amount</td>
      </thead>
      <tbody>
        <tr v-for="item, index in history">
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
</template>
<style scoped>
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
export default {
  created(){
    this.retrieve()
  },
  data(){
    return {
      user: AUTH.user,
      config: CONFIG,
      history: []
    }
  },
  methods: {
    retrieve(){
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
        this.history = response.billing
      })
    }
  }
}
</script>
