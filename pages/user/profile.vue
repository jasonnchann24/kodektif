<template>
  <div>
    <button @click="signOut">Signout</button>
  </div>
</template>

<script>
export default {
  name: 'UserProfile',
  middleware: 'auth',
  methods: {
    async signOut() {
      await this.$auth.logout()
      this.$router.push({ path: '/' })
    }
  }
}
</script>

<style></style>
