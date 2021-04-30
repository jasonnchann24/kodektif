<template>
  <div></div>
</template>

<script>
import { assert } from 'chai'
export default {
  name: 'CoursesTestRunner',
  props: {
    tests: {
      type: Array,
      default: () => []
    }
  },
  methods: {
    runTests() {
      try {
        assert.equal(1, 1, 'not equal')
      } catch (err) {
        console.log(err)
      }
    }
  }
}
</script>

<style></style>
