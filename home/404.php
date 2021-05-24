<?php
  include 'header.php';
?>
<div class="page-holder">
  <div class="page-content">
    <span class="content text-center" style="margin-top: 150px;">
      <h1 class="text-danger">Error 404!</h1>
      <p class="text-gray" style="font-size: 24px;"><?php echo $_SERVER['REQUEST_URI']; ?> does not exist, sorry.</p>
    </span>
  </div>
</div>
<div class="content">
  <span id="return-to-top"><i class="fa fa-chevron-up"></i></span>
</div>
<?php
  include 'footer.php';
?>
