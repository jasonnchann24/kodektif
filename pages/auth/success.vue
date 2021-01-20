<template>
  <div>
    <h1>Success</h1>
  </div>
</template>

<script>
export default {
  name: 'LoginSuccess',

  async mounted() {
    try {
      const res = await this.$axios.$get('/user')
      await this.$auth.setUser(res.data)
      await this.$store.dispatch('SUCCESSFUL_LOGIN')
      this.$router.push({ path: '/', query: {} })
    } catch (err) {
      this.$router.push({ path: '/auth/login', query: {} })
    }
  },
  methods: {}
}
</script>

<style></style>
