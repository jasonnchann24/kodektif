<template>
  <div class="container">
    <div class="row">
      <div class="col">
        <DashboardNav />
      </div>
    </div>
    <div class="row mt-4">
      <div class="col">
        <nuxt-child />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Dashboard',
  middleware: 'onlyAdmin'
}
</script>

<style></style>
