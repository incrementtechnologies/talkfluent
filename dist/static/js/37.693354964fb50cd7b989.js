webpackJsonp([37],{385:function(n,t,o){o(796);var r=o(151)(o(622),o(915),null,null);n.exports=r.exports},622:function(n,t,o){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var r=o(28),e=o(42),i=o(27);t.default={mounted:function(){this.username=this.$route.params.username,this.code=this.$route.params.code,console.log(this.username)},data:function(){return{username:this.$route.params.username,code:this.$route.params.code,errorMessage:null,user:e.a.user,tokenData:e.a.tokenData,config:i.default,flag:!1,text:"Verify your account by clicking the button bellow."}},methods:{redirect:function(n){r.a.push(n)},verify:function(){var n=this,t={username:this.username,code:this.code};this.APIRequest("accounts/verify",t).then(function(t){!0===t.data?(n.text="You've successully verified your account.",n.errorMessage=null,n.flag=!0):(n.flag=!1,n.errorMessage="Unable to Verified this account. Kindly go to your email and check the verification link.")})}}}},720:function(n,t,o){t=n.exports=o(367)(),t.push([n.i,"body{background:#fff}.login-container{margin-top:75px}.login-company-name{width:100%;text-align:center;float:left}.login-company-name img{width:60%;margin-left:20%;margin-right:20%}.login-input-holder{width:100%;float:left;margin-top:10px;background:#fff;margin-bottom:75px;border:1px solid #ddd}.login-input-holder h3{width:100%;float:left;font-size:24px;text-align:center;padding-top:25px;padding-bottom:10px;border-bottom:1px solid #fff;color:#00bff3}.login-input-holder .login-fields{width:80%;float:left;margin:25px 10%}.login-message-holder{min-height:30px;font-size:12px;float:left;overflow:hidden}.login-fields .links{float:left;width:100%;text-align:center;color:#00bff3}.login-fields .links label:hover{cursor:pointer}.login-spacer{margin-bottom:10px}.btn-login,.form-control-login{height:45px!important}.btn-warning{color:#fff!important}@media (max-width:991px){.hide-this{display:none}.login-container{box-shadow:0 0 0 0 #fff!important;margin-top:75px!important}.form-check,.login-company-name{text-align:center!important;width:100%!important}.login-input-holder{width:100%!important;margin-left:0!important}}","",{version:3,sources:["C:/xampp/htdocs/talkfluent/src/modules/home/AccountVerification.vue"],names:[],mappings:"AACA,KACE,eAAiB,CAClB,AACD,iBACE,eAAiB,CAClB,AACD,oBACE,WAAY,AACZ,kBAAmB,AACnB,UAAY,CACb,AACD,wBACE,UAAW,AACX,gBAAiB,AACjB,gBAAkB,CACnB,AACD,oBACE,WAAY,AACZ,WAAY,AACZ,gBAAiB,AACjB,gBAAiB,AACjB,mBAAoB,AACpB,qBAAuB,CACxB,AACD,uBACE,WAAY,AACZ,WAAY,AACZ,eAAgB,AAChB,kBAAmB,AACnB,iBAAkB,AAClB,oBAAqB,AACrB,6BAA8B,AAC9B,aAAe,CAChB,AACD,kCACE,UAAW,AACX,WAAY,AACZ,eAA0B,CAC3B,AACD,sBACE,gBAAiB,AACjB,eAAgB,AAChB,WAAY,AACZ,eAAiB,CAClB,AACD,qBACE,WAAY,AACZ,WAAY,AACZ,kBAAmB,AACnB,aAAe,CAChB,AACD,iCACE,cAAgB,CACjB,AACD,cACE,kBAAoB,CACrB,AAYD,+BACE,qBAAwB,CACzB,AACD,aACE,oBAAuB,CACxB,AAuBD,yBACA,WACI,YAAc,CACjB,AACD,iBACI,kCAAoC,AACpC,yBAA4B,CAC/B,AACD,gCACI,4BAA8B,AAC9B,oBAAuB,CAC1B,AACD,oBACI,qBAAuB,AACvB,uBAA0B,CAC7B,CACA",file:"AccountVerification.vue",sourcesContent:["\nbody{\r\n  background: #fff;\n}\n.login-container{\r\n  margin-top: 75px;\n}\n.login-company-name{\r\n  width: 100%;\r\n  text-align: center;\r\n  float: left;\n}\n.login-company-name img{\r\n  width: 60%;\r\n  margin-left: 20%;\r\n  margin-right: 20%;\n}\n.login-input-holder{\r\n  width: 100%;\r\n  float: left;\r\n  margin-top: 10px;\r\n  background: #fff;\r\n  margin-bottom: 75px;\r\n  border: solid 1px #ddd;\n}\n.login-input-holder h3{\r\n  width: 100%;\r\n  float: left;\r\n  font-size: 24px;\r\n  text-align: center;\r\n  padding-top: 25px;\r\n  padding-bottom: 10px;\r\n  border-bottom: solid 1px #fff;\r\n  color: #00bff3;\n}\n.login-input-holder .login-fields{\r\n  width: 80%;\r\n  float: left;\r\n  margin: 25px 10% 25px 10%;\n}\n.login-message-holder{\r\n  min-height: 30px;\r\n  font-size: 12px;\r\n  float: left;\r\n  overflow: hidden;\n}\n.login-fields .links{\r\n  float: left;\r\n  width: 100%;\r\n  text-align: center;\r\n  color: #00bff3;\n}\n.login-fields .links label:hover{\r\n  cursor: pointer;\n}\n.login-spacer{\r\n  margin-bottom: 10px;\n}\n.form-control-login{\r\n  height: 45px !important;\n}\r\n\r\n\r\n\r\n/*----------------------------------------\r\n\r\n            Buttons\r\n\r\n------------------------------------------*/\n.btn-login{\r\n  height: 45px !important;\n}\n.btn-warning{\r\n  color: #fff !important;\n}\r\n\r\n\r\n/*---------------------------------------------------------\r\n\r\n                  RESPONSIVE HANDLER\r\n\r\n-----------------------------------------------------------*/\r\n\r\n/*-------------- Large Screens for Desktop --------------*/\n@media (min-width: 1200px){\n}\r\n\r\n\r\n/*-------------- Medium Screen for Tablets  --------------*/\n@media screen (min-width: 992px), screen and (max-width: 1199px){\n}\r\n\r\n/*-------------- Small Screen for Mobile Phones  --------------*/\n@media screen (min-width: 768px), screen and (max-width: 991px){\n}\r\n\r\n/*-------------- Extra Small Screen for Mobile Phones --------------*/\n@media (max-width: 991px){\n.hide-this{\r\n    display: none;\n}\n.login-container{\r\n    box-shadow: 0 0 0 0 #fff !important;\r\n    margin-top: 75px !important;\n}\n.login-company-name, .form-check{\r\n    text-align: center !important; \r\n    width: 100% !important;\n}\n.login-input-holder{\r\n    width: 100% !important;\r\n    margin-left: 0 !important;\n}\n}\r\n"],sourceRoot:""}])},796:function(n,t,o){var r=o(720);"string"==typeof r&&(r=[[n.i,r,""]]),r.locals&&(n.exports=r.locals);o(368)("36db3dc2",r,!0)},915:function(n,t){n.exports={render:function(){var n=this,t=n.$createElement,o=n._self._c||t;return o("div",{staticClass:"row"},[o("div",{staticClass:"col-md-6 col-lg-4 mx-auto login-container"},[o("div",{staticClass:"login-input-holder"},[o("h3",[n._v("\n          Verification\n        ")]),n._v(" "),o("div",{staticClass:"login-fields"},[null!==n.errorMessage?o("div",{staticClass:"login-message-holder login-spacer"},[o("span",{staticClass:"text-danger"},[o("b",[n._v("Oops!")]),n._v(" "+n._s(n.errorMessage))])]):n._e(),n._v(" "),o("div",{staticClass:"input-group login-spacer text-center"},[n._v("\n              "+n._s(n.text)+"\n          ")]),n._v(" "),!1===n.flag?o("button",{staticClass:"btn btn-primary btn-block btn-login login-spacer",on:{click:function(t){return n.verify()}}},[n._v("Verify")]):n._e(),n._v(" "),!0===n.flag&&0!==n.user.userID?o("button",{staticClass:"btn btn-primary btn-block btn-login login-spacer",on:{click:function(t){return n.redirect("/dashboard")}}},[n._v("Continue to Dashboard")]):n._e(),n._v(" "),!0===n.flag&&0===n.user.userID?o("button",{staticClass:"btn btn-primary btn-block btn-login login-spacer",on:{click:function(t){return n.redirect("/login")}}},[n._v("Go to Login")]):n._e()])])])])},staticRenderFns:[]}}});
//# sourceMappingURL=37.693354964fb50cd7b989.js.map