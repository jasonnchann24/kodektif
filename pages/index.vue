<template>
  <div class="container">
    <textarea id="code" v-model="code" name="code" cols="30" rows="10">
    </textarea>
    <button @click="runCode">RUN</button>
    <p id="test"></p>
    <div v-if="output">
      <p>{{ output }}</p>
    </div>
  </div>
</template>

<script>
import Worker from '../webworkers/runner.worker'

export default {
  data() {
    return {
      code: 'return 2+2',
      output: null,
      data: []
    }
  },
  methods: {
    runCode() {
      let worker = new Worker(Worker)

      worker.onmessage = function(e) {
        let current_log = console.log

        console.log = (msg) => {
          if (msg !== undefined) this.data.push(msg)
          current_log.apply(null, arguments)
        }
      }

      worker.onerror = function(e) {
        alert(e.message)
        this.output = e.message
      }
      worker.postMessage(this.code)

      setTimeout(function() {
        worker.terminate()
        worker = null
      }, 10000)
    }
  }
}
</script>

<style>
.container {
  margin: 0 auto;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}
</style>
