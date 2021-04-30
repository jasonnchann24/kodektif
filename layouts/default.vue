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

::-webkit-scrollbar {
  width: 18px;
}
::-webkit-scrollbar-track {
  background-color: transparent;
}
::-webkit-scrollbar-thumb {
  background-color: #e59500;
  border: 6px solid transparent;
  background-clip: content-box;
}
::-webkit-scrollbar-thumb:hover {
  background-color: #ffb42a;
}
</style>
