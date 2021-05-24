var username = null;
var password = null;

function login(){
  username = document.getElementById('username').value;
  password = document.getElementById('password').value;
  if(username == null || username == '' || password == null || password == ''){
    $('#error-message-login').css({'display': 'block'});
    $('#error-message-login').html('<b>Opps!</b> Please fillup the required informations.');
  }else{
    // Login Account
    var parameter = {
      'username': username,
      'password': password
    };
    $.post(getHost() + 'authenticate', parameter, function(data, status, xhr){
      setToken(data['token']);
      $('#error-message-login').css({'display': 'none'});
      $('#login').modal('hide');
      $.get(getHost() + 'authenticate/user', {'token': data['token']}, function(userInfo, status, xhr){
        var parameter = {
          'condition': [{
            'value': userInfo.id,
            'clause': '=',
            'column': 'id'
          }]
        };
        $.post(getHost() + 'accounts/retrieve', parameter, function(profileInfo, status, xhr){
          setUser(userInfo.id, userInfo.username, profileInfo.profile);
        });
      });
    }).fail(function(){
      $('#error-message-login').css({'display': 'block'});
      $('#error-message-login').html('<b>Opps!</b> Invalid username and password.');
    });
  }
}