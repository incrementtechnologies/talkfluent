webpackJsonp([20],{374:function(t,e,n){n(773);var r=n(151)(n(610),n(892),"data-v-561b116d",null);t.exports=r.exports},410:function(t,e,n){"use strict";function r(t){return"[object Array]"===w.call(t)}function a(t){return"[object ArrayBuffer]"===w.call(t)}function o(t){return"undefined"!=typeof FormData&&t instanceof FormData}function i(t){return"undefined"!=typeof ArrayBuffer&&ArrayBuffer.isView?ArrayBuffer.isView(t):t&&t.buffer&&t.buffer instanceof ArrayBuffer}function s(t){return"string"==typeof t}function u(t){return"number"==typeof t}function l(t){return void 0===t}function c(t){return null!==t&&"object"==typeof t}function d(t){return"[object Date]"===w.call(t)}function f(t){return"[object File]"===w.call(t)}function p(t){return"[object Blob]"===w.call(t)}function h(t){return"[object Function]"===w.call(t)}function m(t){return c(t)&&h(t.pipe)}function v(t){return"undefined"!=typeof URLSearchParams&&t instanceof URLSearchParams}function g(t){return t.replace(/^\s*/,"").replace(/\s*$/,"")}function b(){return("undefined"==typeof navigator||"ReactNative"!==navigator.product)&&("undefined"!=typeof window&&"undefined"!=typeof document)}function C(t,e){if(null!==t&&void 0!==t)if("object"!=typeof t&&(t=[t]),r(t))for(var n=0,a=t.length;n<a;n++)e.call(null,t[n],n,t);else for(var o in t)Object.prototype.hasOwnProperty.call(t,o)&&e.call(null,t[o],o,t)}function y(){function t(t,n){"object"==typeof e[n]&&"object"==typeof t?e[n]=y(e[n],t):e[n]=t}for(var e={},n=0,r=arguments.length;n<r;n++)C(arguments[n],t);return e}function A(t,e,n){return C(e,function(e,r){t[r]=n&&"function"==typeof e?_(e,n):e}),t}var _=n(416),x=n(435),w=Object.prototype.toString;t.exports={isArray:r,isArrayBuffer:a,isBuffer:x,isFormData:o,isArrayBufferView:i,isString:s,isNumber:u,isObject:c,isUndefined:l,isDate:d,isFile:f,isBlob:p,isFunction:h,isStream:m,isURLSearchParams:v,isStandardBrowserEnv:b,forEach:C,merge:y,extend:A,trim:g}},411:function(t,e,n){"use strict";(function(e){function r(t,e){!a.isUndefined(t)&&a.isUndefined(t["Content-Type"])&&(t["Content-Type"]=e)}var a=n(410),o=n(432),i={"Content-Type":"application/x-www-form-urlencoded"},s={adapter:function(){var t;return"undefined"!=typeof XMLHttpRequest?t=n(412):void 0!==e&&(t=n(412)),t}(),transformRequest:[function(t,e){return o(e,"Content-Type"),a.isFormData(t)||a.isArrayBuffer(t)||a.isBuffer(t)||a.isStream(t)||a.isFile(t)||a.isBlob(t)?t:a.isArrayBufferView(t)?t.buffer:a.isURLSearchParams(t)?(r(e,"application/x-www-form-urlencoded;charset=utf-8"),t.toString()):a.isObject(t)?(r(e,"application/json;charset=utf-8"),JSON.stringify(t)):t}],transformResponse:[function(t){if("string"==typeof t)try{t=JSON.parse(t)}catch(t){}return t}],timeout:0,xsrfCookieName:"XSRF-TOKEN",xsrfHeaderName:"X-XSRF-TOKEN",maxContentLength:-1,validateStatus:function(t){return t>=200&&t<300}};s.headers={common:{Accept:"application/json, text/plain, */*"}},a.forEach(["delete","get","head"],function(t){s.headers[t]={}}),a.forEach(["post","put","patch"],function(t){s.headers[t]=a.merge(i)}),t.exports=s}).call(e,n(83))},412:function(t,e,n){"use strict";var r=n(410),a=n(424),o=n(427),i=n(433),s=n(431),u=n(415),l="undefined"!=typeof window&&window.btoa&&window.btoa.bind(window)||n(426);t.exports=function(t){return new Promise(function(e,c){var d=t.data,f=t.headers;r.isFormData(d)&&delete f["Content-Type"];var p=new XMLHttpRequest,h="onreadystatechange",m=!1;if("undefined"==typeof window||!window.XDomainRequest||"withCredentials"in p||s(t.url)||(p=new window.XDomainRequest,h="onload",m=!0,p.onprogress=function(){},p.ontimeout=function(){}),t.auth){var v=t.auth.username||"",g=t.auth.password||"";f.Authorization="Basic "+l(v+":"+g)}if(p.open(t.method.toUpperCase(),o(t.url,t.params,t.paramsSerializer),!0),p.timeout=t.timeout,p[h]=function(){if(p&&(4===p.readyState||m)&&(0!==p.status||p.responseURL&&0===p.responseURL.indexOf("file:"))){var n="getAllResponseHeaders"in p?i(p.getAllResponseHeaders()):null,r=t.responseType&&"text"!==t.responseType?p.response:p.responseText,o={data:r,status:1223===p.status?204:p.status,statusText:1223===p.status?"No Content":p.statusText,headers:n,config:t,request:p};a(e,c,o),p=null}},p.onerror=function(){c(u("Network Error",t,null,p)),p=null},p.ontimeout=function(){c(u("timeout of "+t.timeout+"ms exceeded",t,"ECONNABORTED",p)),p=null},r.isStandardBrowserEnv()){var b=n(429),C=(t.withCredentials||s(t.url))&&t.xsrfCookieName?b.read(t.xsrfCookieName):void 0;C&&(f[t.xsrfHeaderName]=C)}if("setRequestHeader"in p&&r.forEach(f,function(t,e){void 0===d&&"content-type"===e.toLowerCase()?delete f[e]:p.setRequestHeader(e,t)}),t.withCredentials&&(p.withCredentials=!0),t.responseType)try{p.responseType=t.responseType}catch(e){if("json"!==t.responseType)throw e}"function"==typeof t.onDownloadProgress&&p.addEventListener("progress",t.onDownloadProgress),"function"==typeof t.onUploadProgress&&p.upload&&p.upload.addEventListener("progress",t.onUploadProgress),t.cancelToken&&t.cancelToken.promise.then(function(t){p&&(p.abort(),c(t),p=null)}),void 0===d&&(d=null),p.send(d)})}},413:function(t,e,n){"use strict";function r(t){this.message=t}r.prototype.toString=function(){return"Cancel"+(this.message?": "+this.message:"")},r.prototype.__CANCEL__=!0,t.exports=r},414:function(t,e,n){"use strict";t.exports=function(t){return!(!t||!t.__CANCEL__)}},415:function(t,e,n){"use strict";var r=n(423);t.exports=function(t,e,n,a,o){var i=new Error(t);return r(i,e,n,a,o)}},416:function(t,e,n){"use strict";t.exports=function(t,e){return function(){for(var n=new Array(arguments.length),r=0;r<n.length;r++)n[r]=arguments[r];return t.apply(e,n)}}},417:function(t,e,n){t.exports=n(418)},418:function(t,e,n){"use strict";function r(t){var e=new i(t),n=o(i.prototype.request,e);return a.extend(n,i.prototype,e),a.extend(n,e),n}var a=n(410),o=n(416),i=n(420),s=n(411),u=r(s);u.Axios=i,u.create=function(t){return r(a.merge(s,t))},u.Cancel=n(413),u.CancelToken=n(419),u.isCancel=n(414),u.all=function(t){return Promise.all(t)},u.spread=n(434),t.exports=u,t.exports.default=u},419:function(t,e,n){"use strict";function r(t){if("function"!=typeof t)throw new TypeError("executor must be a function.");var e;this.promise=new Promise(function(t){e=t});var n=this;t(function(t){n.reason||(n.reason=new a(t),e(n.reason))})}var a=n(413);r.prototype.throwIfRequested=function(){if(this.reason)throw this.reason},r.source=function(){var t;return{token:new r(function(e){t=e}),cancel:t}},t.exports=r},420:function(t,e,n){"use strict";function r(t){this.defaults=t,this.interceptors={request:new i,response:new i}}var a=n(411),o=n(410),i=n(421),s=n(422);r.prototype.request=function(t){"string"==typeof t&&(t=o.merge({url:arguments[0]},arguments[1])),t=o.merge(a,this.defaults,{method:"get"},t),t.method=t.method.toLowerCase();var e=[s,void 0],n=Promise.resolve(t);for(this.interceptors.request.forEach(function(t){e.unshift(t.fulfilled,t.rejected)}),this.interceptors.response.forEach(function(t){e.push(t.fulfilled,t.rejected)});e.length;)n=n.then(e.shift(),e.shift());return n},o.forEach(["delete","get","head","options"],function(t){r.prototype[t]=function(e,n){return this.request(o.merge(n||{},{method:t,url:e}))}}),o.forEach(["post","put","patch"],function(t){r.prototype[t]=function(e,n,r){return this.request(o.merge(r||{},{method:t,url:e,data:n}))}}),t.exports=r},421:function(t,e,n){"use strict";function r(){this.handlers=[]}var a=n(410);r.prototype.use=function(t,e){return this.handlers.push({fulfilled:t,rejected:e}),this.handlers.length-1},r.prototype.eject=function(t){this.handlers[t]&&(this.handlers[t]=null)},r.prototype.forEach=function(t){a.forEach(this.handlers,function(e){null!==e&&t(e)})},t.exports=r},422:function(t,e,n){"use strict";function r(t){t.cancelToken&&t.cancelToken.throwIfRequested()}var a=n(410),o=n(425),i=n(414),s=n(411),u=n(430),l=n(428);t.exports=function(t){return r(t),t.baseURL&&!u(t.url)&&(t.url=l(t.baseURL,t.url)),t.headers=t.headers||{},t.data=o(t.data,t.headers,t.transformRequest),t.headers=a.merge(t.headers.common||{},t.headers[t.method]||{},t.headers||{}),a.forEach(["delete","get","head","post","put","patch","common"],function(e){delete t.headers[e]}),(t.adapter||s.adapter)(t).then(function(e){return r(t),e.data=o(e.data,e.headers,t.transformResponse),e},function(e){return i(e)||(r(t),e&&e.response&&(e.response.data=o(e.response.data,e.response.headers,t.transformResponse))),Promise.reject(e)})}},423:function(t,e,n){"use strict";t.exports=function(t,e,n,r,a){return t.config=e,n&&(t.code=n),t.request=r,t.response=a,t}},424:function(t,e,n){"use strict";var r=n(415);t.exports=function(t,e,n){var a=n.config.validateStatus;n.status&&a&&!a(n.status)?e(r("Request failed with status code "+n.status,n.config,null,n.request,n)):t(n)}},425:function(t,e,n){"use strict";var r=n(410);t.exports=function(t,e,n){return r.forEach(n,function(n){t=n(t,e)}),t}},426:function(t,e,n){"use strict";function r(){this.message="String contains an invalid character"}function a(t){for(var e,n,a=String(t),i="",s=0,u=o;a.charAt(0|s)||(u="=",s%1);i+=u.charAt(63&e>>8-s%1*8)){if((n=a.charCodeAt(s+=.75))>255)throw new r;e=e<<8|n}return i}var o="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";r.prototype=new Error,r.prototype.code=5,r.prototype.name="InvalidCharacterError",t.exports=a},427:function(t,e,n){"use strict";function r(t){return encodeURIComponent(t).replace(/%40/gi,"@").replace(/%3A/gi,":").replace(/%24/g,"$").replace(/%2C/gi,",").replace(/%20/g,"+").replace(/%5B/gi,"[").replace(/%5D/gi,"]")}var a=n(410);t.exports=function(t,e,n){if(!e)return t;var o;if(n)o=n(e);else if(a.isURLSearchParams(e))o=e.toString();else{var i=[];a.forEach(e,function(t,e){null!==t&&void 0!==t&&(a.isArray(t)&&(e+="[]"),a.isArray(t)||(t=[t]),a.forEach(t,function(t){a.isDate(t)?t=t.toISOString():a.isObject(t)&&(t=JSON.stringify(t)),i.push(r(e)+"="+r(t))}))}),o=i.join("&")}return o&&(t+=(-1===t.indexOf("?")?"?":"&")+o),t}},428:function(t,e,n){"use strict";t.exports=function(t,e){return e?t.replace(/\/+$/,"")+"/"+e.replace(/^\/+/,""):t}},429:function(t,e,n){"use strict";var r=n(410);t.exports=r.isStandardBrowserEnv()?function(){return{write:function(t,e,n,a,o,i){var s=[];s.push(t+"="+encodeURIComponent(e)),r.isNumber(n)&&s.push("expires="+new Date(n).toGMTString()),r.isString(a)&&s.push("path="+a),r.isString(o)&&s.push("domain="+o),!0===i&&s.push("secure"),document.cookie=s.join("; ")},read:function(t){var e=document.cookie.match(new RegExp("(^|;\\s*)("+t+")=([^;]*)"));return e?decodeURIComponent(e[3]):null},remove:function(t){this.write(t,"",Date.now()-864e5)}}}():function(){return{write:function(){},read:function(){return null},remove:function(){}}}()},430:function(t,e,n){"use strict";t.exports=function(t){return/^([a-z][a-z\d\+\-\.]*:)?\/\//i.test(t)}},431:function(t,e,n){"use strict";var r=n(410);t.exports=r.isStandardBrowserEnv()?function(){function t(t){var e=t;return n&&(a.setAttribute("href",e),e=a.href),a.setAttribute("href",e),{href:a.href,protocol:a.protocol?a.protocol.replace(/:$/,""):"",host:a.host,search:a.search?a.search.replace(/^\?/,""):"",hash:a.hash?a.hash.replace(/^#/,""):"",hostname:a.hostname,port:a.port,pathname:"/"===a.pathname.charAt(0)?a.pathname:"/"+a.pathname}}var e,n=/(msie|trident)/i.test(navigator.userAgent),a=document.createElement("a");return e=t(window.location.href),function(n){var a=r.isString(n)?t(n):n;return a.protocol===e.protocol&&a.host===e.host}}():function(){return function(){return!0}}()},432:function(t,e,n){"use strict";var r=n(410);t.exports=function(t,e){r.forEach(t,function(n,r){r!==e&&r.toUpperCase()===e.toUpperCase()&&(t[e]=n,delete t[r])})}},433:function(t,e,n){"use strict";var r=n(410),a=["age","authorization","content-length","content-type","etag","expires","from","host","if-modified-since","if-unmodified-since","last-modified","location","max-forwards","proxy-authorization","referer","retry-after","user-agent"];t.exports=function(t){var e,n,o,i={};return t?(r.forEach(t.split("\n"),function(t){if(o=t.indexOf(":"),e=r.trim(t.substr(0,o)).toLowerCase(),n=r.trim(t.substr(o+1)),e){if(i[e]&&a.indexOf(e)>=0)return;i[e]="set-cookie"===e?(i[e]?i[e]:[]).concat([n]):i[e]?i[e]+", "+n:n}}),i):i}},434:function(t,e,n){"use strict";t.exports=function(t){return function(e){return t.apply(null,e)}}},435:function(t,e){function n(t){return!!t.constructor&&"function"==typeof t.constructor.isBuffer&&t.constructor.isBuffer(t)}function r(t){return"function"==typeof t.readFloatLE&&"function"==typeof t.slice&&n(t.slice(0,0))}/*!
 * Determine if an object is a Buffer
 *
 * @author   Feross Aboukhadijeh <https://feross.org>
 * @license  MIT
 */
t.exports=function(t){return null!=t&&(n(t)||r(t)||!!t._isBuffer)}},440:function(t,e,n){t.exports={default:n(441),__esModule:!0}},441:function(t,e,n){var r=n(12),a=r.JSON||(r.JSON={stringify:JSON.stringify});t.exports=function(t){return a.stringify.apply(a,arguments)}},610:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var r=n(440),a=n.n(r),o=n(28),i=n(42),s=n(417),u=n.n(s),l=n(27);e.default={mounted:function(){i.a.redirect(),this.getAudio()},data:function(){return{user:i.a.user,tokenData:i.a.tokenData,modalTitle:"Add Audio File",audioFiles:[],errorMessage:null,closeFag:!1,file:null,title:null,modalFile:null,modalFileInput:{id:null,title:null}}},methods:{redirect:function(t){o.a.push(t)},setUpFileUpload:function(t){var e=t.target.files||t.dataTransfer.files;if(!e.length)return!1;this.file=e[0],this.createAudio(e[0])},createAudio:function(t){(new FileReader).readAsDataURL(event.target.files[0])},submit:function(){!0===this.validation()?(this.errorMessage=null,this.sendRequest()):this.errorMessage="Please fillup the required information"},sendRequest:function(){var t=this,e=new FormData;e.append("audio_file",this.file),e.append("filename",this.file.name),u.a.post(l.default.BACKEND_URL+"/audio_file/create",e).then(function(e){if(100===e.data.error.status){var n=e.data.error.message;"undefined"!==n.word?t.errorMessage=n.word[0]:t.errorMessage=a()(n)}else e.data.result?(t.closeFag=!0,o.a.go("/audio_files")):t.closeFag=!1})},getAudio:function(){var t=this;this.APIRequest("audio_file/retrieve").then(function(e){t.audioFiles=e.data})},deleteAccent:function(t){var e={id:t};this.APIRequest("audio_file/delete",e).then(function(t){o.a.go("/audio_files")})},validation:function(){return null!==this.url},play:function(t){this.audio=new Audio(l.default.BACKEND_URL+t),this.audio.play()},pause:function(t){this.audio.pause()},updateModal:function(t){this.modalFile=this.audioFiles[t],this.modalFileInput.id=this.modalFile.id},updateAccent:function(){var t=this,e=new FormData;e.append("id",this.modalFileInput.id),null!==this.file&&(e.append("filename",this.file.name),e.append("audio_file",this.file)),u.a.post(l.default.BACKEND_URL+"/audio_file/update",e).then(function(e){if(100===e.data.error.status){var n=e.data.error.message;"undefined"!==n.word?t.errorMessage=n.word[0]:t.errorMessage=a()(n)}else!0===e.data.data?(t.closeFag=!0,o.a.go("/audio_files")):t.closeFag=!1})}}}},697:function(t,e,n){e=t.exports=n(367)(),e.push([t.i,".result-holder[data-v-561b116d]{color:#000;margin-bottom:5%;height:150px}.history[data-v-561b116d]{margin-top:25px}.bg-primary[data-v-561b116d]{background:#1caceb!important}.modal-title i[data-v-561b116d]{padding-right:10px}.form-control[data-v-561b116d]{height:45px!important}td button i[data-v-561b116d]{padding-right:10px}thead[data-v-561b116d]{font-weight:700}.modal-body[data-v-561b116d]{width:98%!important;margin:0 1%!important}","",{version:3,sources:["C:/xampp/htdocs/talkfluent/src/modules/audio/AudioFile.vue"],names:[],mappings:"AACA,gCACE,WAAY,AACZ,iBAAkB,AAClB,YAAc,CACf,AACD,0BACE,eAAiB,CAClB,AACD,6BACE,4BAA+B,CAChC,AACD,gCACE,kBAAoB,CACrB,AACD,+BACE,qBAAwB,CACzB,AACD,6BACE,kBAAoB,CACrB,AACD,uBACE,eAAiB,CAClB,AACD,6BACE,oBAAsB,AACtB,qBAAiC,CAClC",file:"AudioFile.vue",sourcesContent:["\n.result-holder[data-v-561b116d]{\r\n  color: #000;\r\n  margin-bottom: 5%;\r\n  height: 150px;\n}\n.history[data-v-561b116d]{\r\n  margin-top: 25px;\n}\n.bg-primary[data-v-561b116d]{\r\n  background: #1caceb !important;\n}\n.modal-title i[data-v-561b116d]{\r\n  padding-right: 10px;\n}\n.form-control[data-v-561b116d]{\r\n  height: 45px !important;\n}\ntd button i[data-v-561b116d]{\r\n  padding-right: 10px;\n}\nthead[data-v-561b116d]{\r\n  font-weight: 700;\n}\n.modal-body[data-v-561b116d]{\r\n  width: 98% !important;\r\n  margin: 0px 1% 0px 1% !important;\n}\r\n"],sourceRoot:""}])},773:function(t,e,n){var r=n(697);"string"==typeof r&&(r=[[t.i,r,""]]),r.locals&&(t.exports=r.locals);n(368)("15f7ae80",r,!0)},892:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"row"},[n("div",{staticClass:"col-lg-10 mx-auto"},[n("ul",{staticClass:"breadcrumb"},[n("li",{staticClass:"breadcrumb-item active",on:{click:function(e){return t.redirect("/dashboard")}}},[n("a",{attrs:{href:"#",onclick:"return false;"}},[t._v("Dashboard")])]),t._v(" "),n("li",{staticClass:"breadcrumb-item active"},[t._v("Audio File Management")])])]),t._v(" "),t._m(0),t._v(" "),n("div",{staticClass:"col-lg-10 mx-auto history"},[n("br"),t._v(" "),n("table",{staticClass:"table table-responsive"},[t._m(1),t._v(" "),0!==t.audioFiles.length?n("tbody",t._l(t.audioFiles,function(e,r){return n("tr",[n("td",[t._v(t._s(e.url+e.filename))]),t._v(" "),n("td",[n("button",{staticClass:"btn btn-primary",on:{click:function(n){return t.play(e.url+e.filename)}}},[t._v("Play")]),t._v(" "),n("button",{staticClass:"btn btn-primary",attrs:{"data-toggle":"modal","data-target":"#editModal"},on:{click:function(e){return t.updateModal(r)}}},[n("i",{staticClass:"fa fa-pencil"}),t._v("Edit")]),t._v(" "),n("button",{staticClass:"btn btn-danger",on:{click:function(n){return t.deleteAccent(e.id)}}},[n("i",{staticClass:"fa fa-trash"}),t._v("Delete")])])])}),0):n("tbody",[t._m(2)])])]),t._v(" "),null!==t.modalFile?n("div",{staticClass:"modal fade",attrs:{id:"editModal",tabindex:"-1",role:"dialog","aria-labelledby":"exampleModalLabel","aria-hidden":"true"}},[n("div",{staticClass:"modal-dialog modal-lg",attrs:{role:"document"}},[n("div",{staticClass:"modal-content"},[t._m(3),t._v(" "),n("div",{staticClass:"modal-body"},[null!==t.errorMessage?n("span",{staticClass:"text-danger text-center"},[n("label",[n("b",[t._v("Opps! ")]),t._v(t._s(t.errorMessage))])]):t._e(),t._v(" "),null!==t.errorMessage?n("br"):t._e(),t._v(" "),null!==t.errorMessage?n("br"):t._e(),t._v(" "),n("br"),t._v(" "),n("label",[t._v("File")]),t._v(" "),n("br"),t._v(" "),n("input",{staticClass:"form-control",attrs:{type:"file",name:"title",accept:"audio/*",placeholder:"Audio File"},on:{change:function(e){return t.setUpFileUpload(e)}}})]),t._v(" "),n("div",{staticClass:"modal-footer"},[0==t.closeFag?n("button",{staticClass:"btn btn-primary",attrs:{type:"button"},on:{click:function(e){return t.updateAccent()}}},[t._v("update")]):n("button",{staticClass:"btn btn-danger",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[t._v("Close")])])])])]):t._e(),t._v(" "),n("div",{staticClass:"modal fade",attrs:{id:"myModal",tabindex:"-1",role:"dialog","aria-labelledby":"exampleModalLabel","aria-hidden":"true"}},[n("div",{staticClass:"modal-dialog modal-lg",attrs:{role:"document"}},[n("div",{staticClass:"modal-content"},[n("div",{staticClass:"modal-header bg-primary"},[n("h5",{staticClass:"modal-title",attrs:{id:"exampleModalLabel"}},[n("i",{staticClass:"fa fa-ellipsis-v"}),t._v(t._s(t.modalTitle))]),t._v(" "),t._m(4)]),t._v(" "),n("div",{staticClass:"modal-body"},[null!==t.errorMessage?n("span",{staticClass:"text-danger text-center"},[n("label",[n("b",[t._v("Opps! ")]),t._v(t._s(t.errorMessage))])]):t._e(),t._v(" "),null!==t.errorMessage?n("br"):t._e(),t._v(" "),null!==t.errorMessage?n("br"):t._e(),t._v(" "),n("br"),t._v(" "),n("label",[t._v("File")]),t._v(" "),n("br"),t._v(" "),n("input",{staticClass:"form-control",attrs:{type:"file",name:"title",accept:"audio/*",placeholder:"Audio File"},on:{change:function(e){return t.setUpFileUpload(e)}}})]),t._v(" "),n("div",{staticClass:"modal-footer"},[0==t.closeFag?n("button",{staticClass:"btn btn-primary",attrs:{type:"button"},on:{click:function(e){return t.submit()}}},[t._v("Submit")]):n("button",{staticClass:"btn btn-danger",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[t._v("Close")])])])])])])},staticRenderFns:[function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"col-lg-10 mx-auto"},[n("button",{staticClass:"btn btn-primary pull-right",attrs:{"data-toggle":"modal","data-target":"#myModal"}},[n("i",{staticClass:"fa fa-plus"}),t._v(" Add")])])},function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("thead",[n("tr",[n("td",[t._v("URL")]),t._v(" "),n("td",[t._v("Action")])])])},function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("tr",[n("td",{staticClass:"text-danger text-center",attrs:{colspan:"3"}},[t._v("Empty")])])},function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"modal-header bg-primary"},[n("h5",{staticClass:"modal-title",attrs:{id:"exampleModalLabel"}},[n("i",{staticClass:"fa fa-ellipsis-v"}),t._v("Update File")]),t._v(" "),n("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[n("span",{staticClass:"text-white",attrs:{"aria-hidden":"true"}},[t._v("×")])])])},function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[n("span",{staticClass:"text-white",attrs:{"aria-hidden":"true"}},[t._v("×")])])}]}}});
//# sourceMappingURL=20.ca4ab9dde61d45f02b74.js.map