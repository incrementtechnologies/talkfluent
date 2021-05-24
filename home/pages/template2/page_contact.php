<div class="page-holder">
  <span class="header-title hide-on-mobile">
    <img src="<?php echo $host.'/img/header.png'?>" >
    <div class="title-holder">
      <div class="title">Contact Us</div>
    </div>
  </span>
  <span class="header-title-mobile show-on-mobile">
    <img src="<?php echo $host.'/img/header.png'?>" >
    <label>Contact Us</label>
  </span>
  <div class="page-content-template2">
    <span class="details" style="margin-top: 50px;">Get in touch with us. We’ll be happy to answer any questions you have within 24 hours.</span>
    <span class="error-holder" id="messageContainer"></span>
    <input type="text" class="form-control form-control-lg form-control-custom" placeholder="Full Name" id="fullName">
    <input type="text" class="form-control form-control-lg form-control-custom" placeholder="Email Address" id="email">
    <input type="text" class="form-control form-control-lg form-control-custom" placeholder="Subject" id="subject">
    <textarea class="form-control text-area-custom" placeholder="Message" id="message"></textarea>
    <button class="btn btn-primary" style="margin-left: 10%;margin-bottom: 50px;" onclick="submit()">Submit</button>
  </div>
</div>
<div class="customModal" id="loadingModal">
  <span class="loading">
    <i class="fas fa-spinner fa-spin"></i>
  </span>
</div>