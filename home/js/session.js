function setToken(token){
  localStorage.setItem('usertoken', token);
}
function setUser(id, username, profile){
  localStorage.setItem('id', id);
  localStorage.setItem('username', username);
  localStorage.setItem('profile', profile);
}

function setDisplayUserInfo(username){
  $('#lead-buttons').css({'display': 'none'});
  $('#user-info').css({'display': 'block'});
  $('#username-display').html('<b> Hi ' + username + '!</b>');
}
function updateItem(key, value){
  localStorage.setItem(key, value);
}

function getSessionItem(key){
  return localStorage.getItem(key);
}

function removeSessionItem(key){
  localStorage.removeItem(key);
}

function clearSession(){
  localStorage.clear();
  $('#lead-buttons').css({'display': 'block'});
  $('#user-info').css({'display': 'none'});
}

function getHost(){
  var host = window.location.href.split('/')[2];
  var url = '';
  if(host == 'localhost'){
    url = 'http://localhost/talkfluent/api/public/';
  }else if(host == 'hablainglesfluido.com'){
    url = 'https://api.hablainglesfluido.com/api/public/'
  }else if(host == 'talkfluentspanish.com'){
    url = 'https://api.talkfluentspanish.com/api/public/'
  }
  return url;
}

function validateItem(item){
  if(item == null || item == ''){
    return false;
  }else{
    return true;
  }
}

function refreshUserInfo(){
  var username = getSessionItem('username');
}

function refreshSession(){
  setTimeout(function(){
    $.get(getHost() + 'authenticate/refresh', {'token': getSessionItem('token')}, function(response, status, xhr){
      setToken(response['token']);
    })
  }, 1000 * 60 * 50);
}

window.onload = function(){
  refreshSession();
  refreshUserInfo();
}