<template>
  <div class="overlay d-flex align-items-center justify-content-center">
    <SelfBuildingSquareSpinner
      :animation-duration="2000"
      :size="90"
      color="#e59500"
    />
  </div>
</template>

<script>
export default {
  name: 'LoadingOverlay'
}
</script>

<style>
.overlay {
  background-color: #474b60a8;
  position: fixed;
  width: 100%;
  height: 100%;
  z-index: 1000;
  top: 0px;
  left: 0px;
  /* opacity: 0.7; */
}
</style>
