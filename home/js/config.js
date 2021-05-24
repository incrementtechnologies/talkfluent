var host = window.location.href.split('/')[2];
var BACKEND_URL = '';
if(host == 'localhost'){
  BACKEND_URL = 'http://localhost/talkfluent/api/public/';
}else if(host == 'hablainglesfluido.com'){
  BACKEND_URL = 'https://api.hablainglesfluido.com/api/public/';
}else if(host == 'talkfluentspanish.com'){
  BACKEND_URL = 'https://api.talkfluentspanish.com/api/public/';
}
export default {BACKEND_URL: BACKEND_URL};