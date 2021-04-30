<template>
  <div class="editor">
    <EditorContent class="editor__content" :editor="editor" />
  </div>
</template>

<script>
import { Editor, EditorContent } from 'tiptap'

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
  History,
  Image
} from 'tiptap-extensions'

import javascript from 'highlight.js/lib/languages/javascript'
import css from 'highlight.js/lib/languages/css'

export default {
  name: 'CoreHtmlViewer',
  components: {
    EditorContent
  },
  props: {
    bodyContent: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      editor: new Editor({
        extensions: [
          new Image(),
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
        content: '<p>Body Content</p>',
        editable: false
      })
    }
  },
  mounted() {
    this.refreshContent()
  },
  beforeDestroy() {
    this.editor.destroy()
  },
  methods: {
    refreshContent() {
      this.editor.setContent(this.bodyContent)
    }
  }
}
</script>

<style lang="scss">
.editor {
  position: relative;
  width: 95%;
  &__content img {
    max-width: 100%;
  }
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

button.is-active {
  background-color: rgba(184, 184, 184, 0.714);
}

.ProseMirror {
  padding: 25px;
  background: #343747;
}

code {
  background-color: rgba(76, 76, 76, 0.843);
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
