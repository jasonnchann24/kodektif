<template>
  <div></div>
</template>

<script>
export default {
  name: 'NotFoundRedirect',
  middleware: [
    function({ redirect }) {
      return redirect(301, '/')
    }
  ]
}
</script>

<style></style>
