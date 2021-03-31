<template>
  <div>
    <div v-if="ARTICLE.data" class="container">
      <div class="row">
        <div class="col">
          <h1>{{ article.title }}</h1>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <client-only>
            <CoreHtmlViewer :body-content="article.body" />
          </client-only>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  name: 'ArticleSlug',
  async fetch() {
    this.UPDATE_LOADING()
    try {
      await this.GET_ARTICLE({
        id: this.$route.params.id,
        slug: this.$route.params.slug
      })
    } catch (err) {
      this.$toast.error(`Error! ${err.response.statusText}`)
    } finally {
      this.UPDATE_LOADING(false)
    }
  },
  computed: {
    ...mapGetters({
      ARTICLE: 'articles/ARTICLE'
    }),
    article() {
      return this.ARTICLE.data
    }
  },
  methods: {
    ...mapActions({
      GET_ARTICLE: 'articles/GET_ARTICLE',
      UPDATE_LOADING: 'UPDATE_LOADING'
    })
  }
}
</script>

<style></style>
