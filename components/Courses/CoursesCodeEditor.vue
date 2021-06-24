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
            :readonly="!isAuthenticated"
            @update="updateCode"
            @ctrlEnter="executeCode"
          />
        </client-only>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col">
        <button
          class="btn btn-primary"
          :class="{ disabled: !isAuthenticated }"
          :disabled="!isAuthenticated"
          @click="executeCode"
        >
          Execute
        </button>
        <button
          class="btn btn-success text-white"
          :class="{ disabled: !isAuthenticated }"
          :disabled="!isAuthenticated"
          @click="submitCode"
        >
          Submit
        </button>
        <p v-if="!isAuthenticated" class="mt-3 mb-0 text-danger">
          Please login to be able to finish this course!
        </p>
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
import { mapGetters } from 'vuex'
export default {
  name: 'CoursesCodeEditor',
  props: {
    document: {
      type: Object,
      default: () => {},
      required: true
    }
  },
  data() {
    return {
      code: '',
      output: ''
    }
  },
  computed: {
    ...mapGetters({
      isAuthenticated: 'isAuthenticated'
    })
  },
  created() {
    this.code = this.document.initial_code
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

        this.output = res.output ?? 'No output please print/log something.'
        this.$toast.clear()
      } catch (err) {
        this.$toast.error('Something went wrong')
        console.log(err)
      }
    },
    async submitCode() {
      this.output = ''
      let tests = this.document.test_cases
      let allPass = true
      for (let i = 0; i < tests.length; i++) {
        let runnerCode = this.code
        runnerCode +=
          this.document.function_name + `(${tests[i].input.join(',')});`
        try {
          const res = await this.$axios.$post(this.$config.CODE_RUNNER_URL, {
            language: 'js',
            source: runnerCode
          })

          let passIcon = ''
          if (res.output.replace(/[\r\n]/g, '') == tests[i].expect.toString()) {
            passIcon = '-- PASS ✔'
          } else {
            passIcon = '-- FAIL ✘'
            allPass = false
          }

          this.output += `Output Test #${i + 1} : ${res.output} ${passIcon}\n`
        } catch (err) {
          this.$toast.error('Something went wrong')
          console.log(err)
        }

        await this.$delay(650)
      }

      this.$emit('evaluated', {
        pass: allPass,
        code: this.code
      })
    }
  }
}
</script>

<style></style>
