<template>
  <title>{{config.WEBSITE_TITLE}}</title>
</template>
<script>
import CONFIG from 'config.js'
export default {
  data() {
    return {
      config: CONFIG
    }
  }
}
</script>
