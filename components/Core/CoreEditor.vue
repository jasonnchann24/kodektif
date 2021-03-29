<template>
  <div class="editor">
    <EditorFloatingMenu v-slot="{ commands, isActive, menu }" :editor="editor">
      <div
        class="editor__floating-menu"
        :class="{ 'is-active': menu.isActive }"
        :style="`top: ${menu.top}px`"
      >
        <button
          class="btn btn-link text-white"
          :class="{ 'is-active': isActive.bold() }"
          @click="commands.bold"
        >
          <i class="ri-bold"></i>
        </button>

        <button
          class="btn btn-link text-white"
          :class="{ 'is-active': isActive.italic() }"
          @click="commands.italic"
        >
          <i class="ri-italic"></i>
        </button>

        <button
          class="btn btn-link text-white"
          :class="{ 'is-active': isActive.strike() }"
          @click="commands.strike"
        >
          <i class="ri-strikethrough"></i>
        </button>

        <button
          class="btn btn-link text-white"
          :class="{ 'is-active': isActive.underline() }"
          @click="commands.underline"
        >
          <i class="ri-underline"></i>
        </button>

        <button
          class="btn btn-link text-white"
          :class="{ 'is-active': isActive.code() }"
          @click="commands.code"
        >
          <i class="ri-code-line"></i>
        </button>

        <button
          class="btn btn-link text-white"
          :class="{ 'is-active': isActive.paragraph() }"
          @click="commands.paragraph"
        >
          <i class="ri-paragraph"></i>
        </button>

        <button
          class="btn btn-link text-white"
          :class="{ 'is-active': isActive.heading({ level: 1 }) }"
          @click="commands.heading({ level: 1 })"
        >
          H1
        </button>

        <button
          class="btn btn-link text-white"
          :class="{ 'is-active': isActive.heading({ level: 2 }) }"
          @click="commands.heading({ level: 2 })"
        >
          H2
        </button>

        <button
          class="btn btn-link text-white"
          :class="{ 'is-active': isActive.heading({ level: 3 }) }"
          @click="commands.heading({ level: 3 })"
        >
          H3
        </button>

        <button
          class="btn btn-link text-white"
          :class="{ 'is-active': isActive.bullet_list() }"
          @click="commands.bullet_list"
        >
          <i class="ri-list-unordered"></i>
        </button>

        <button
          class="btn btn-link text-white"
          :class="{ 'is-active': isActive.ordered_list() }"
          @click="commands.ordered_list"
        >
          <i class="ri-list-ordered"></i>
        </button>

        <button
          class="btn btn-link text-white"
          :class="{ 'is-active': isActive.blockquote() }"
          @click="commands.blockquote"
        >
          <i class="ri-double-quotes-l"></i>
        </button>

        <button
          class="btn btn-link text-white"
          :class="{ 'is-active': isActive.code_block() }"
          @click="commands.code_block"
        >
          <i class="ri-code-box-line"></i>
        </button>

        <button
          class="btn btn-link text-white"
          @click="commands.horizontal_rule"
        >
          <i class="ri-separator"></i>
        </button>

        <button class="btn btn-link text-white" @click="commands.undo">
          <i class="ri-arrow-go-back-line"></i>
        </button>

        <button class="btn btn-link text-white" @click="commands.redo">
          <i class="ri-arrow-go-forward-line"></i>
        </button>
      </div>
    </EditorFloatingMenu>
    <EditorContent class="editor__content mt-4" :editor="editor" />
  </div>
</template>

<script>
import { Editor, EditorContent, EditorFloatingMenu } from 'tiptap'
import Image from '@/plugins/tipTapImage'

import {
  CodeBlockHighlight,
  Blockquote,
  CodeBlock,
  HardBreak,
  Heading,
  HorizontalRule,
  OrderedList,
  BulletList,
  ListItem,
  TodoItem,
  TodoList,
  Bold,
  Code,
  Italic,
  Link,
  Strike,
  Underline,
  History
} from 'tiptap-extensions'

import javascript from 'highlight.js/lib/languages/javascript'
import css from 'highlight.js/lib/languages/css'

async function upload(file) {
  let formData = new FormData()
  formData.append('file', file)
  const headers = { 'Content-Type': 'multipart/form-data' }
  const response = await axios.post('/upload', formData, { headers: headers })
  return response.data.src
}

export default {
  name: 'TipTapEditor',
  components: {
    EditorContent,
    EditorFloatingMenu
  },
  data() {
    return {
      editor:
        new Editor({
          extensions: [
            new Image(null, null, upload),
            new CodeBlockHighlight({
              languages: {
                javascript,
                css
              }
            }),
            new Blockquote(),
            new BulletList(),
            new CodeBlock(),
            new HardBreak(),
            new Heading({ levels: [1, 2, 3] }),
            new HorizontalRule(),
            new ListItem(),
            new OrderedList(),
            new TodoItem(),
            new TodoList(),
            new Link(),
            new Bold(),
            new Code(),
            new Italic(),
            new Strike(),
            new Underline(),
            new History()
          ],
          content: '<p>test</p>'
        }) ?? null
    }
  },
  beforeDestroy() {
    this.editor.destroy()
  }
}
</script>

<style lang="scss">
.editor {
  position: relative;
  &__floating-menu {
    position: absolute;
    z-index: 1;
    margin-top: 0rem;
    margin-left: 25px;
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.2s, visibility 0.2s;
    &.is-active {
      opacity: 1;
      visibility: visible;
    }
  }
}

.ProseMirror {
  padding: 25px;
  background: #343747;
}

pre {
  &::before {
    content: attr(data-language);
    text-transform: uppercase;
    display: block;
    text-align: right;
    font-weight: bold;
    font-size: 0.6rem;
  }
  code {
    .hljs-comment,
    .hljs-quote {
      color: #999999;
    }
    .hljs-variable,
    .hljs-template-variable,
    .hljs-attribute,
    .hljs-tag,
    .hljs-name,
    .hljs-regexp,
    .hljs-link,
    .hljs-name,
    .hljs-selector-id,
    .hljs-selector-class {
      color: #f2777a;
    }
    .hljs-number,
    .hljs-meta,
    .hljs-built_in,
    .hljs-builtin-name,
    .hljs-literal,
    .hljs-type,
    .hljs-params {
      color: #f99157;
    }
    .hljs-string,
    .hljs-symbol,
    .hljs-bullet {
      color: #99cc99;
    }
    .hljs-title,
    .hljs-section {
      color: #ffcc66;
    }
    .hljs-keyword,
    .hljs-selector-tag {
      color: #6699cc;
    }
    .hljs-emphasis {
      font-style: italic;
    }
    .hljs-strong {
      font-weight: 700;
    }
  }
}
</style>
