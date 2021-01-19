<template>
  <div>
    <h1>Success</h1>
  </div>
</template>

<script>
export default {
  name: 'LoginSuccess',

  mounted() {
    this.$auth.strategy.token.set('Bearer ' + this.$route.query.access_token)
    this.$auth.fetchUser().then(() => {
      this.$router.push({ path: '/', query: {} })
    })
  },
  methods: {}
}
</script>

<style></style>
