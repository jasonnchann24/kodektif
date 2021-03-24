<template>
  <div>
    <CoreNavbar />
    <main id="top-main">
      <nuxt />
    </main>
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

#top-main {
  min-height: 60vh;
  width: 100vw;
}
</style>
