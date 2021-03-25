<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col">
          <h1>Articles</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-8">
          <div class="row g-3">
            <template v-if="!$fetchState.pending">
              <div
                v-for="(item, index) in ARTICLES.data"
                :key="index"
                class="col-6"
              >
                <div class="card" style="height: 11rem">
                  <div class="card-body text-dark">
                    <h5>{{ item.title }}</h5>
                    <p style="height: 3rem">
                      {{ item.description }}
                    </p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                  </div>
                </div>
              </div>
            </template>
            <template v-else>
              <p class="h5">Loading ...</p>
            </template>
          </div>
        </div>

        <div class="col-12 col-md-4 d-none d-md-block d-flex flex-column">
          <img
            src="~/assets/svgs/articles.svg"
            alt="article image"
            class="w-100"
          />
          <div class="w-50 border-bottom border-danger mx-auto mt-2"></div>
          <div class="mt-3">
            <BasePagination module="articles" getter="ARTICLES" />
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
    await this.GET_ARTICLES({ page: this.$route.query.page ?? 1 })
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
