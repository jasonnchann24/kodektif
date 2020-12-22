<template>
  <div class="container">
    <h1 class="text-primary">Login</h1>
    <b-button @click="login('github')"
      ><i class="lni lni-github-original"></i
    ></b-button>
  </div>
</template>

<script>
export default {
  name: 'Login',
  layout: 'auth',
  methods: {
    login(provider) {
      window.location.href = `${process.env.BASE_URL}/login/${provider}`
    }
  }
}
</script>
