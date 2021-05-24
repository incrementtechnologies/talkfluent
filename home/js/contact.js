var input = {
  fullname: null,
  subject: null,
  email: null,
  message: null
}
var errorMessage = null;
var url = null;

function getValues(){
  input.fullname = document.getElementById('fullName').value;
  input.email = document.getElementById('email').value;
  input.subject = document.getElementById('subject').value;
  input.message = document.getElementById('message').value;
}

function validation(){
  getValues();
  if(input.fullname == null || input.fullname == "" || input.subject == null || input.subject == "" || input.email == null || input.email == "" || input.message == null || input.message == ""){
    return false;
  }else{
    return true;
  }   
}

function setError(){
  var message = '<b class=text-danger>Opps!</b> <label class=text-danger>Please fill up the required fields!</label>';
  $('#messageContainer').css({'display': 'block'})
  $('#messageContainer').html(message);
}

function setSuccess(){
  var message = '<b class=text-success>Yes!</b> <label class=text-success>Successfully Submitted!</label>';
  $('#messageContainer').css({'display': 'block'})
  $('#messageContainer').html(message);
}

function showLoadingModal(){
  $('#loadingModal').css({'display': 'block'});
}

function hideLoadingModal(){
  $('#loadingModal').css({'display': 'none'});
}

function submit(){
  if(validation() == true){
    showLoadingModal();
    $.ajax({
      url: getHost() + 'contacts/create', 
      data: input,
      success: function(data){
        if(parseInt(data.result) > 0){
          hideLoadingModal();
          setSuccess();
        }else{
          setError();
        }
      },
      type: 'POST',
      xhr: function(){
        var xhr = new window.XMLHttpRequest();
        xhr.addEventListener("progress", function(evt){
        if (evt.lengthComputable) {
          var percentComplete = evt.loaded / evt.total;
            if(percentComplete == 1){
              hideLoadingModal();
            }
          }
        }, false);
        return xhr;
      }
    });
  }else{
    setError();
  }
}