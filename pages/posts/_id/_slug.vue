<template>
  <div>
    <div v-if="!$fetchState.pending">
      <div class="container">
        <div class="row">
          <div class="col">
            <h1>{{ POST.title }}</h1>
          </div>
        </div>
      </div>
      <div class="container">
        <article>
          <div class="row">
            <div class="col-6">
              <div class="row d-flex align-items-center my-2">
                <div class="col-2 me-3">
                  <img
                    :src="POST.user.provider.avatar"
                    class="rounded-circle"
                    style="width: 75px"
                    alt="author avatar"
                  />
                </div>
                <div class="col-7">
                  <p class="mb-0">by {{ POST.author }}</p>
                  <p class="text-muted mb-0">
                    {{ $moment(POST.created_at).format('dddd, DD MMM YYYY') }}
                  </p>
                </div>
              </div>
              <!-- <p class="d-flex align-items-center">
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
            </p> -->
            </div>
          </div>
          <div class="row mt-4">
            <div class="col">
              <h5>
                {{ POST.description }}
              </h5>
            </div>
          </div>
          <div class="row mt-4">
            <div class="col">
              <client-only>
                <CoreHtmlViewer :body-content="POST.body" />
              </client-only>
            </div>
          </div>
        </article>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
export default {
  name: 'PostShow',
  async fetch() {
    await this.GET_POST({
      id: this.$route.params.id,
      slug: this.$route.params.slug
    })
  },
  computed: {
    ...mapGetters({
      POST: 'posts/POST'
    })
  },
  methods: {
    ...mapActions({
      GET_POST: 'posts/GET_POST'
    })
  }
}
</script>

<style></style>
