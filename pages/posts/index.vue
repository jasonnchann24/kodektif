<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col">
          <h1>User Posts</h1>
        </div>
      </div>
      <div class="row g-4 mt-2">
        <div
          v-for="(post, index) in POSTS.data"
          :key="index"
          class="col-12 col-md-6 col-lg-4"
        >
          <div class="card bg-primary">
            <div class="card-body">
              <h3 class="text-truncate">{{ post.title }}</h3>
              <small class="text-warning">{{ post.language.name }}</small>
              <p class="text-truncate">{{ post.description }}</p>
              <NuxtLink
                :to="`/posts/${post.id}/${post.slug}`"
                class="btn btn-sm btn-info float-end text-white"
              >
                Read More ...
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
      <div v-if="POSTS.meta" class="row mt-3">
        <BasePagination
          module="posts"
          getter="POSTS"
          action="GET_POSTS"
          :total-pages="POSTS.meta.last_page"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  name: 'IndexPosts',
  async fetch() {
    try {
      this.$store.dispatch('UPDATE_LOADING', true)

      await this.GET_POSTS({})
    } catch (err) {
      this.$toast.error(err.response.statusText)
    } finally {
      this.$store.dispatch('UPDATE_LOADING', false)
    }
  },
  computed: {
    ...mapGetters({
      POSTS: 'posts/POSTS'
    })
  },
  methods: {
    ...mapActions({
      GET_POSTS: 'posts/GET_POSTS',
      UPDATE_LOADING: 'UPDATE_LOADING'
    })
  }
}
</script>

<style></style>
