<template>
  <div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12" :class="{ 'col-lg-6': !isIntro }">
          <div :class="{ container: isIntro }">
            <nuxt-content :document="doc" />
          </div>
        </div>
        <div class="col-12 col-lg-6" :class="{ 'd-none': isIntro }">
          <div class="row">
            <div class="col">
              <client-only>
                <CoreCodeEditor :initial-code="code" @update="updateCode" />
              </client-only>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <button class="btn btn-primary" @click="executeCode">
                Execute
              </button>
            </div>
          </div>
        </div>
      </div>
      <div v-if="COURSE" class="row mt-5">
        <div
          class="col-11 mx-auto d-flex align-items-center justify-content-between"
        >
          <div v-if="getPrevChapter()">
            <NuxtLink
              :to="
                `/courses/${COURSE.id}/${COURSE.slug}/${getPrevChapter().slug}`
              "
              type="button"
              class="btn btn-primary text-decoration-none text-white d-flex align-items-center"
            >
              <i class="ri-arrow-left-s-line me-1"></i>
              <div>Previous chapter: {{ getPrevChapter().title }}</div>
            </NuxtLink>
          </div>
          <div v-else></div>
          <NuxtLink
            :to="
              `/courses/${COURSE.id}/${COURSE.slug}/${getNextChapter().slug}`
            "
            type="button"
            class="btn btn-primary text-decoration-none text-white d-flex align-items-center"
          >
            <div>Next chapter: {{ getNextChapter().title }}</div>
            <i class="ri-arrow-right-s-line ms-1"></i>
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  name: 'CourseChapter',
  async asyncData({ $content, app, route }) {
    const doc = await $content(
      `${app.i18n.localeProperties.code}/${route.params.course}/${route.params.chapter}`,
      {
        deep: true
      }
    ).fetch()
    return { doc }
  },
  data() {
    return {
      code: 'function test(){\n  console.log("test")\n}\ntest();'
    }
  },
  async fetch() {
    await this.GET_COURSE(this.$route.params.id)
  },
  head() {
    return {
      title: `${this.doc.chapter} - ${this.doc.course}`,
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: `${this.doc.description}`
        }
      ]
    }
  },
  computed: {
    ...mapGetters({
      COURSE: 'courses/COURSE'
    }),
    isIntro() {
      if (!Object.keys(this.COURSE)) {
        return true
      }
      let chapterOrder = this.COURSE.chapters.find((obj) => {
        return obj.slug == this.$route.params.chapter
      })

      return chapterOrder.order == 0
    }
  },
  methods: {
    ...mapActions({
      GET_COURSE: 'courses/GET_COURSE'
    }),
    updateCode(val) {
      this.code = val
    },
    getNextChapter() {
      const currentOrder = this.doc.order
      const chapters = this.COURSE.chapters
      let nextChapter = chapters.find((x) => {
        return x.order == currentOrder + 1
      })
      if (!nextChapter) {
        nextChapter = {
          title: 'Finish',
          slug: 'finish'
        }
      }
      return nextChapter
    },
    getPrevChapter() {
      const currentOrder = this.doc.order
      const chapters = this.COURSE.chapters
      let prevChapter = chapters.find((x) => {
        return x.order == currentOrder - 1
      })
      if (!prevChapter) {
        prevChapter = false
      }
      return prevChapter
    },
    executeCode() {
      this.$axios.$post('https://emkc.org/api/v1/piston/execute', {
        language: 'js',
        source: this.code
      })
    }
  }
}
</script>

<style>
.nuxt-content-highlight {
  max-width: 95%;
  margin: auto;
}

.nuxt-content code {
  color: #f67e7d;
}
</style>
