<template>
  <div class="container">
    <h1 class="text-primary">Login</h1>
    <button @click="login('github')">
      <i class="lni lni-github-original"></i>
    </button>
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
      window.location.href = `${process.env.BASE_URL}/login/${provider}`
    }
  }
}
</script>
