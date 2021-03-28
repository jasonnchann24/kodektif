<template>
  <div>
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
                <div class="card" style="height: 11rem">
                  <div class="card-body text-dark">
                    <h5 class="text-truncate">{{ item.title }}</h5>
                    <p style="height: 3rem">
                      {{ item.description }}
                    </p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
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
          <div v-if="ARTICLES.meta" class="mt-3">
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
</style>
