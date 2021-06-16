<template>
  <div class="holder">
    <div class="content-holder">
      <b style="line-height: 50px;">Cart Summary</b>

      <div class="content-body" v-if="plan !== null">
        <div class="footer-item">
          <span class="pull-left">
            <b>{{plan.amount + ' ' + plan.subLabel}}</b>
            <br />
            {{plan.description}}
          </span>
          <span class="pull-right">
            <b>{{plan.currency + ' ' + plan.amount}}</b>
          </span>
        </div>
      </div>

      <div class="payment-methods" v-if="paymentMethod !== null">
        <span class="payment-item">
          <b style="padding-left: 10px;">Pay via {{paymentMethod.title}}</b>
          <span class="pull-right" style="padding-top: 9px;">
            <i v-for="(iItem, iIndex) in paymentMethod.icons" :class="iItem" :key="iIndex" style="padding-left: 5px;"></i>
          </span>
        </span>
        <p class="description">
          {{paymentMethod.description}}
        </p>
      </div>


      <div class="content-footer">

        <div class="footer-item">
          <span class="pull-left" style="padding-left: 20%;">
            Subtotal
          </span>
          <span class="pull-right">
            <b>$99.00</b>
          </span>
        </div>


        <div class="footer-item">
          <span class="pull-left" style="padding-left: 20%;">
            <b>Total</b>
          </span>
          <span class="pull-right">
            <b>$99.00</b>
          </span>
        </div>

        <button class="btn btn-primary btn-block">
          Authorize
        </button>

      </div>
    </div>
  </div>
</template>
<style scoped>
.holder{
  width: 100%;
  float: left;
}

.content-holder{
  width: 100%;
  float: right;
  border-radius: 5px;
  border:  solid 1px #eee;
  min-height: 200px;
  overflow-y: hidden;
  padding: 20px;
}

.content-body{
  width: 100%;
  float: left;
}
.content-footer{
  width: 100%;
  float: left;
  border-top:  solid 1px #eee;
}

.footer-item{
  line-height: 50px;
  width: 100%;
  float: left;
}


.fab{
  font-size: 32px;
}


.payment-methods{
  float: left;
  width: 100%;
  cursor: pointer;
  padding:  20px;
  background-color: #eee;
}

.payment-methods:hover{
  background-color: #eee;
}

.payment-item{
  float: left;
  width: 100%;
  line-height: 50px;
  cursor: pointer;
}

.description{
  color:  #999;
}


.btn-block{
  height: 50px !important;
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
      config: CONFIG
    }
  },
  props: ['paymentMethod', 'plan'],
  methods: {
  }
}
</script>
