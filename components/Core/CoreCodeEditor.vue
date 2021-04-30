<template>
  <PrismEditor
    v-model="code"
    class="my-editor"
    :highlight="highlighter"
    line-numbers
    :readonly="readonly"
    @keyup="passEvent"
  ></PrismEditor>
</template>

<script>
// import Prism Editor
import { PrismEditor } from 'vue-prism-editor'
import 'vue-prism-editor/dist/prismeditor.min.css' // import the styles somewhere

// import highlighting library (you can use any library you want just return html string)
import { highlight, languages } from 'prismjs/components/prism-core'
import 'prismjs/components/prism-clike'
import 'prismjs/components/prism-javascript'
import 'prismjs/themes/prism-tomorrow.css' // import syntax highlighting styles

export default {
  components: {
    PrismEditor
  },
  props: {
    initialCode: {
      type: String,
      default: 'function(){\n\n\n\n}'
    },
    readonly: {
      type: Boolean,
      default: false
    },
    output: {
      type: String,
      default: ''
    }
  },
  data: () => ({ code: '' }),
  watch: {
    code() {
      this.$emit('update', this.code)
    },
    output() {
      this.code = this.output
    }
  },
  mounted() {
    if (this.initialCode) {
      this.code = this.initialCode
    }
  },
  methods: {
    highlighter(code) {
      return highlight(code, languages.js) //returns html
    },
    passEvent(event) {
      const isExecute = event.key == 'Enter' && event.ctrlKey == true
      if (isExecute) {
        this.$emit('ctrlEnter')
      }
    }
  }
}
</script>

<style>
.my-editor {
  background: #2d2d2d;
  color: #ccc;

  font-family: Fira code, Fira Mono, Consolas, Menlo, Courier, monospace;
  font-size: 14px;
  line-height: 1.5;
  padding: 5px;

  caret-color: white;
  max-width: 95%;
}

.prism-editor__textarea:focus {
  outline: none;
}
</style>
