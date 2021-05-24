function subscribes(){
  var name = document.getElementById('subscribes_name').value;
  var email = document.getElementById('subscribes_email').value;
  var error = "Please fill up the required informations.";
  var success = "Successfully Subscribe";
  var emailError = "Email is already"

  if(name == null || email == null){
    // display error
    $('#subscribes_error').css({'color': '#FF0000'});
    $('#subscribes_error').html(error);
  }else{
    var parameter = {
      'name': name,
      'email': email,
      'status': 'active'
    };
    $.post(getHost() + 'subscriptions/create', parameter, function(data, status, xhr){
      if(data.data == null){
        error = data.error.message.email[0];
        $('#subscribes_error').css({'color': '#FF0000'});
        $('#subscribes_error').html(error);
      }else{
        $('#subscribes_error').css({'color': '#39b54a'});
        $('#subscribes_error').html(success);
      }
    }).fail(function(){
      $('#subscribes_error').css({'color': '#FF0000'});
      $('#subscribes_error').html(error);
    });
  }
}