<template>
  <div class="container">
    <textarea id="code" v-model="code" name="code" cols="30" rows="10">
    </textarea>
    <button @click="initWorker">RUN</button>
    <button @click="stopWorker">STOP</button>
    <p id="test"></p>
    <div v-if="output">
      <p>{{ output }}</p>
    </div>
    <p v-if="error">{{ error }}</p>
  </div>
</template>

<script>
import Worker from '../webworkers/runner.worker'
export default {
  data() {
    return {
      code: 'return 2+2',
      output: null,
      data: [],
      error: null,
      vueWorker: null,
      newCode: ''
    }
  },
  methods: {
    sendResult(result) {
      console.log(JSON.stringify(result) + ' from send')
    },
    initWorker() {
      this.output = null
      this.error = null
      this.vueWorker = null
      this.vueWorker = new Worker(Worker)
      this.runCode()
        .then(() => console.log('yes'))
        .catch((e) => console.log(e))
    },
    stopWorker() {
      if (this.vueWorker) {
        this.vueWorker.terminate()
        this.vueWorker = null
      }
    },

    runCode() {
      return new Promise((resolve, reject) => {
        let handle = setTimeout(() => {
          this.stopWorker()
          reject('timeout')
        }, 500)

        this.vueWorker.postMessage(this.code)

        this.vueWorker.onmessage = (e) => {
          this.output = e.data.result
          this.sendResult(e.data)
          clearTimeout(handle)
          resolve(e.data.result)
        }

        this.vueWorker.onerror = (e) => {
          this.output = e.message
          clearTimeout(handle)
          reject(e.message)
        }
      })
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
