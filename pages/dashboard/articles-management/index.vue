<template>
  <div>
    <div class="row">
      <div class="col">
        <nuxt-link
          to="/dashboard/articles-management/create"
          tag="button"
          class="btn btn-primary d-flex align-items-center float-end"
        >
          <i class="ri-add-box-line me-2"></i> <span>New Article</span>
        </nuxt-link>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th class="row-title" scope="col">Title</th>
              <th scope="col">Created at</th>
              <th scope="col">Created by</th>
              <th class="text-center" scope="col">Actions</th>
            </tr>
          </thead>
          <tbody v-if="ARTICLES.data">
            <tr v-for="article in ARTICLES.data" :key="article.id">
              <th scope="row">{{ article.id }}</th>
              <td class="row-title">{{ article.title }}</td>
              <td>{{ $moment(article.created_at).format('DD MMM YYYY') }}</td>
              <td>{{ article.author }}</td>
              <td>
                <div class="row">
                  <div
                    class="col d-flex align-items-center justify-content-evenly"
                  >
                    <button class="btn btn-success text-white">
                      Update
                    </button>
                    <button class="btn btn-danger text-white">
                      Delete
                    </button>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div v-if="ARTICLES.meta" class="row mt-3">
      <div class="col d-flex align-items-center justify-content-end">
        <BasePagination
          module="articles"
          getter="ARTICLES"
          action="GET_ARTICLES"
          :total-pages="ARTICLES.meta.last_page"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  name: 'ArticlesManagement',
  middleware: 'onlyAdmin',
  computed: {
    ...mapGetters({
      ARTICLES: 'articles/ARTICLES'
    })
  },
  async mounted() {
    this.UPDATE_LOADING(true)

    try {
      await this.GET_ARTICLES({})
    } catch (err) {
      this.$toast.error('Something went wrong')
    } finally {
      this.UPDATE_LOADING(false)
    }
  },
  methods: {
    ...mapActions({
      GET_ARTICLES: 'articles/GET_ARTICLES',
      UPDATE_LOADING: 'UPDATE_LOADING'
    })
  }
}
</script>

<style scoped>
.row-title {
  text-align: left;
}
</style>
