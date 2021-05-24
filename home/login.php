<!-- The Modal -->
<div class="modal" id="login">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Login Account</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <span id="error-message-login" class="error-message text-danger">
        </span>
        <div class="input-group mb-2">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="fa fa-user"></i></div>
          </div>
          <input type="text" class="form-control form-control-login" id="username" placeholder="Username or Email">
        </div>
        <div class="input-group mb-2">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="fa fa-key"></i></div>
          </div>
          <input type="password" class="form-control form-control-login" id="password" placeholder="********">
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="login()">Login</button>
      </div>

    </div>
  </div>
</div>