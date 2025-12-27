<template>
  <div class="mask"></div>
</template>

<script setup>

</script>

<style scoped>
  .mask {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,.5);
    z-index: 900;
  }
</style>
