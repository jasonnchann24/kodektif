<template>
  <div>
    <CoreNavbar />
    <div class="p-4 mb-5 overflow-hidden">
      <nuxt />
    </div>
    <CoreFooter />
  </div>
</template>

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
</style>
