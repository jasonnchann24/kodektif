<template>
  <div
    id="login-section"
    class="container d-flex align-items-center justify-content-center"
  >
    <div class="row">
      <div
        class="col d-flex flex-column align-items-center justify-content-center"
      >
        <div class="my-4">
          <nuxt-link to="/">
            <BaseLogo width="150" />
          </nuxt-link>
        </div>
        <h1 class="h3">LOGIN</h1>
        <button
          class="btn btn-outline-light d-flex align-items-center mt-3"
          @click="login('github')"
        >
          <span>by Github</span> <i class="ri-github-line ri-lg ms-2"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Login',
  layout: 'auth',
  middleware: 'auth',
  auth: 'guest',
  methods: {
    login(provider) {
      this.$toast.info('Please wait ...')
      window.location.href = this.$config.BACKEND_URL + `/login/${provider}`
    }
  }
}
</script>

<style scoped>
#login-section {
  width: 100vw;
  min-height: 100vh;
}
</style>
