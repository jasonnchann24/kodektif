<template>
  <div>
    <transition name="slowFade" mode="out-in">
      <CoreLoadingOverlay v-if="isLoading" />
    </transition>
    <CoreNavbar />
    <main id="top-main">
      <nuxt class="py-4 container-fluid" />
    </main>
    <CoreFooter />
  </div>
</template>

<script>
export default {
  name: 'DefaultLayout',
  computed: {
    isLoading() {
      return this.$store.getters.isLoading
    }
  }
}
</script>
<style>
html {
  margin: 0;
  padding: 0;
  width: 100vw;
  min-height: 100vh;
}
body {
  width: 100%;
  min-height: 100%;
  font-family: 'Montserrat', sans-serif;
}
input,
textarea {
  color: black !important;
}

#top-main {
  min-height: 60vh;
  width: 100%;
}

.slowFade-enter-active,
.slowFade-leave-active {
  transition: opacity 0.4s ease-in-out;
}
.slowFade-enter,
.slowFade-leave-to {
  opacity: 0;
}

.vue-treeselect__menu {
  color: black;
}
</style>
