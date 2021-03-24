<template>
  <img src="/logo/logo_kodektif.svg" alt="logo kodektif" :width="width" />
</template>

<script>
export default {
  name: 'BaseLogo',
  props: {
    width: {
      type: String,
      default: '30'
    }
  }
}
</script>

<style></style>
