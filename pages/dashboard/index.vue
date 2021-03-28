<template>
  <div>
    Admin
  </div>
</template>

<script>
export default {
  name: 'Dashboard',
  middleware: 'onlyAdmin'
}
</script>

<style></style>
