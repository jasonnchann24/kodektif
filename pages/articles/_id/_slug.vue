<template>
  <div v-if="allSet">
    <div class="container">
      <div class="row">
        <div class="col">
          <h1>{{ article.title }}</h1>
        </div>
      </div>
      <div class="row">
        <div class="col text-center my-4">
          <img
            v-if="article.article_image"
            class="article-banner"
            :src="`${$config.BACKEND_URL}${article.article_image}`"
            alt=""
          />
          <img
            v-else
            src="@/static/undraw/to_the_moon.png"
            class="article-banner"
            alt=""
          />
        </div>
      </div>
    </div>
    <div class="container">
      <article>
        <div class="row">
          <div class="col">
            <p class="mb-0">by {{ article.author }}</p>
            <p class="text-muted">
              {{ $moment(article.created_at).format('dddd, DD MMM YYYY') }}
            </p>
            <p class="d-flex align-items-center">
              <i
                v-if="!article.has_liked"
                class="ri-thumb-up-line ri-lg me-3"
                @click="handleLike"
              ></i>
              <i
                v-else
                class="ri-thumb-up-fill ri-lg me-3"
                @click="handleUnlike"
              ></i>

              <span>{{ article.likes_count }}</span>
            </p>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <client-only>
              <CoreHtmlViewer :body-content="article.body" />
            </client-only>
          </div>
        </div>
      </article>
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
    },
    allSet() {
      if (Object.keys(this.ARTICLE).length > 0 && this.ARTICLE.data) {
        return this.ARTICLE.data.id == this.$route.params.id
      } else {
        return false
      }
    }
  },
  methods: {
    ...mapActions({
      GET_ARTICLE: 'articles/GET_ARTICLE',
      UPDATE_LOADING: 'UPDATE_LOADING',
      LIKE_ARTICLE: 'articles/LIKE_ARTICLE',
      UNLIKE_ARTICLE: 'articles/UNLIKE_ARTICLE'
    }),
    async handleLike() {
      this.UPDATE_LOADING()
      try {
        await this.LIKE_ARTICLE(this.$route.params.id)
      } catch (err) {
        this.$toast.error(err.response.statusText)
      } finally {
        this.UPDATE_LOADING(false)
      }
    },
    async handleUnlike() {
      this.UPDATE_LOADING()
      try {
        await this.UNLIKE_ARTICLE(this.article.has_liked.id)
      } catch (err) {
        this.$toast.error(err.response.statusText)
      } finally {
        this.UPDATE_LOADING(false)
      }
    }
  }
}
</script>

<style scoped>
.article-banner {
  object-fit: cover;
  object-position: center;
  width: 50%;
}

.ri-thumb-up-line,
.ri-thumb-up-fill {
  cursor: pointer;
}
</style>
