<template>
  <nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
      <li class="page-item">
        <a class="page-link" href="#">Previous</a>
      </li>
      <li class="page-item"><a class="page-link" href="#">1</a></li>
      <li class="page-item"><a class="page-link" href="#">2</a></li>
      <li class="page-item"><a class="page-link" href="#">3</a></li>
      <li class="page-item">
        <a class="page-link" href="#">Next</a>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: 'BasePagination',
  props: {
    module: {
      type: String,
      default: ''
    },
    getter: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      currentData: null
    }
  },

  computed: {
    getData() {
      return this.$store.getters[`${this.module}/${this.getter}`]
    }
  }
}
</script>

<style></style>
