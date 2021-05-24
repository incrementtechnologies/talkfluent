var reply = null;
var comment = null;
var prevToggleId = null;
var replyId = null;

function validationReply(){
  reply = document.getElementById('reply-text-' + replyId).value;
  if(reply == null || reply == ''){
    return false;
  }else{
    return true;
  }
}

function validationComment(){
  comment = document.getElementById('comment-text').value;
  if(comment == null || comment == ''){
    return false;
  }else{
    return true;
  }
}

function toggleReply(text, id){
  replyId = id;
  id = text + id;
  if(prevToggleId == null){
    prevToggleId = id;
    $('#' + id).css({'display': 'block'});
   }else{
    if(prevToggleId == id){
      $('#' + prevToggleId).css({'display': 'none'});
      prevToggleId = null;
    }else{
      $('#' + prevToggleId).css({'display': 'none'});
      $('#' + id).css({'display': 'block'});
      prevToggleId = id;
    }
  }
}

function showLoadingModalBlog(){
  $('#loadingModalBlog').css({'display': 'block'});
}

function hideLoadingModalBlog(){
  $('#loadingModalBlog').css({'display': 'none'});
}

function submitReply(commentId, name, email, profile, using, userId){
  var input = {
    'reply': document.getElementById('reply-text-' + replyId).value,
    'name': name,
    'email': (email == '' || email == null) ? 'none' : email,
    'profile_url': profile,
    'using': using,
    'user': userId,
    'blog_comment_id': commentId
  };
  if(validationReply() == true){
    showLoadingModalBlog();
    $.ajax({
      url: getHost() + 'blog_comment_replies/create', 
      data: input,
      success: function(data){
        if(parseInt(data.result) > 0){
          hideLoadingModalBlog();
          location.reload();
        }
      },
      type: 'POST',
      xhr: function(){
        var xhr = new window.XMLHttpRequest();
        xhr.addEventListener("progress", function(evt){
        if (evt.lengthComputable) {
          var percentComplete = evt.loaded / evt.total;
            if(percentComplete == 1){
              hideLoadingModalBlog();
            }
          }
        }, false);
        return xhr;
      }
    });
  }else{
  }
}

function submitComment(id, name, email, profile, using, userId){
  var input = {
    'comment': document.getElementById('comment-text').value,
    'name': name,
    'email': (email == '' || email == null) ? 'none' : email,
    'profile_url': profile,
    'using': using,
    'user': userId,
    'blog_id': id
  };
  if(validationComment() == true){
    showLoadingModalBlog();
    $.ajax({
      url: getHost() + 'blog_comments/create',
      data: input,
      success: function(data){
        if(parseInt(data.result) > 0){
          hideLoadingModalBlog();
          location.reload();
        }
      },
      type: 'POST',
      xhr: function(){
        var xhr = new window.XMLHttpRequest();
        xhr.addEventListener("progress", function(evt){
        if (evt.lengthComputable) {
          var percentComplete = evt.loaded / evt.total;
            if(percentComplete == 1){
              hideLoadingModalBlog();
            }
          }
        }, false);
        return xhr;
      }
    });
  }else{
  }
}

function redirect(url){
  window.location.href = url
}

