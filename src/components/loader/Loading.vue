<template>
  <div>
    <div class="customModal" id="loading">
      <span class="loading-holder">
        <i class="fas fa-circle-notch fa-spin"></i>
      </span>
    </div>
  </div>
</template>
<script>
</script>
<style scoped>
.customModal{
  position: fixed;
  background-color: rgba(254, 254, 254, 0);
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  z-index: 100000 !important;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  transition: all 0.3s;
  text-align: center;
  display: none;
}
.customModal .loading-holder{
  font-size: 50px;
  height: 100vh;
  float: left;
  width: 20%;
  margin-left: 40%;
  margin-right: 40%;
  text-align: center;
  color: #00bff3;
  line-height: 100vh;
}
.fa-spin {
  animation-duration: 0.75s;
}
</style>
