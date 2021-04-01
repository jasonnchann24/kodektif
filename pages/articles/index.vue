<template>
  <div id="articlesTop">
    <div class="container">
      <div class="row">
        <div class="col">
          <h1>Articles</h1>
        </div>
      </div>
      <div class="row mt-3 justify-content-center">
        <div class="col-12 col-md-8 order-2 order-lg-1 mt-3 mt-lg-0">
          <div class="row g-3">
            <template v-if="!$fetchState.pending">
              <div
                v-for="(item, index) in ARTICLES.data"
                :key="index"
                class="col-12 col-lg-6"
              >
                <div class="card shadow-lg">
                  <img
                    v-if="item.article_image"
                    class="card-img-top"
                    :src="`${$config.BACKEND_URL}${item.article_image}`"
                    :alt="`main image for ${item.title}`"
                    srcset=""
                  />
                  <img
                    v-else
                    class="card-img-top"
                    src="undraw/to_the_moon.png"
                    alt="image not found"
                  />
                  <div class="card-body text-dark">
                    <h5 class="text-truncate">{{ item.title }}</h5>
                    <p class="my-2" style="height: 3rem">
                      {{ item.description }}
                    </p>
                    <p class="mb-0">
                      <strong>Language</strong>: {{ item.language.name }}
                    </p>
                    <p class="mb-0">
                      <strong>Categories</strong>:
                      <span
                        v-for="cat in item.categories"
                        :key="`${item.id}${cat.id}`"
                      >
                        {{ cat.label }} |</span
                      >
                    </p>
                    <p class="mb-2">
                      <i class="ri-thumb-up-fill me-3"></i
                      ><span>{{ item.likes_count }}</span>
                    </p>

                    <NuxtLink
                      :to="`/articles/${item.id}/${item.slug}`"
                      class="btn btn-primary"
                      >Read more ...</NuxtLink
                    >
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>

        <div
          class="col-12 col-lg-4 order-1 order-lg-2 d-flex flex-column align-items-center"
        >
          <img
            src="~/assets/svgs/articles.svg"
            alt="article image"
            class="w-100"
          />
          <div class="w-50 border-bottom border-danger mx-auto mt-2"></div>
          <div v-if="ARTICLES.meta" class="mt-3 d-none d-lg-block">
            <BasePagination
              module="articles"
              getter="ARTICLES"
              action="GET_ARTICLES"
              :total-pages="ARTICLES.meta.last_page"
            />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div v-if="ARTICLES.meta" class="mt-3 d-block d-lg-none">
            <BasePagination
              module="articles"
              getter="ARTICLES"
              action="GET_ARTICLES"
              :total-pages="ARTICLES.meta.last_page"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  name: 'ArticlesIndex',
  async fetch() {
    this.$store.dispatch('UPDATE_LOADING', true)
    try {
      await this.GET_ARTICLES({ page: this.$route.query.page ?? 1 })
    } catch (err) {
      console.log(err)
      this.$toast.error('Error! ' + err.response.statusText)
    } finally {
      this.$store.dispatch('UPDATE_LOADING', false)
    }
  },
  computed: {
    ...mapGetters({
      ARTICLES: 'articles/ARTICLES'
    })
  },
  methods: {
    ...mapActions({
      GET_ARTICLES: 'articles/GET_ARTICLES'
    })
  }
}
</script>

<style scoped>
p {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}
.card {
  height: 485px;
  border: none;
}

.card-img-top {
  height: 235px;
  object-fit: cover;
  object-position: center;
}
</style>
