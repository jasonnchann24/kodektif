<template>
  <div>
    <div class="row">
      <div class="col">
        <h5>Code Editor</h5>
      </div>
    </div>
    <div class="row">
      <div class="col" style="height: 40vh;">
        <client-only>
          <CoreCodeEditor
            :initial-code="code"
            @update="updateCode"
            @ctrlEnter="executeCode"
          />
        </client-only>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col">
        <button class="btn btn-primary" @click="executeCode">
          Execute
        </button>
        <button class="btn btn-success text-white">
          Submit
        </button>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col">
        <h5>Output</h5>
      </div>
    </div>
    <div class="row">
      <div class="col" style="height: 30vh;">
        <client-only>
          <CoreCodeEditor initial-code="" :readonly="true" :output="output" />
        </client-only>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CoursesCodeEditor',
  data() {
    return {
      code: 'function test(a,b){\n  return a + b;\n}\nconsole.log(test(1,2));',
      output: ''
    }
  },
  methods: {
    updateCode(val) {
      this.code = val
    },
    async executeCode() {
      this.$toast('Executing code...')
      try {
        const res = await this.$axios.$post(this.$config.CODE_RUNNER_URL, {
          language: 'js',
          source: this.code
        })

        this.output = res.output
        this.$toast.clear()
      } catch (err) {
        this.$toast.error('Something went wrong')
        console.log(err)
      }
    }
  }
}
</script>

<style></style>
