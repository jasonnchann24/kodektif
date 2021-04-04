<template>
  <div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12" :class="{ 'col-lg-6': !isIntro }">
          <nuxt-content :document="doc" />
        </div>
        <div class="col-12 col-lg-6" :class="{ 'd-none': isIntro }">
          <client-only>
            <CoreCodeEditor @update="updateCode" />
          </client-only>
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
      code: ''
    }
  },
  async fetch() {
    await this.GET_COURSE(this.$route.params.id)
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
    }
  }
}
</script>

<style>
pre {
  max-width: 95%;
}
</style>
