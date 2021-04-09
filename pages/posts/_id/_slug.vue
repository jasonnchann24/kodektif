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
              <div class="row mt-3 d-flex align-items-center">
                <div class="col-2">
                  <p class="d-flex align-items-center">
                    <i
                      v-if="!POST.has_voted || POST.has_voted.upvote != true"
                      class="ri-thumb-up-line ri-lg me-3"
                      @click="handlePostVote('up')"
                    ></i>
                    <i
                      v-else-if="POST.has_voted.upvote == true"
                      class="ri-thumb-up-fill ri-lg me-3"
                      @click="deletePostVote()"
                    ></i>

                    <span>{{ POST.upvote_count }}</span>
                  </p>
                </div>
                <div class="col-2">
                  <p class="d-flex align-items-center">
                    <i
                      v-if="!POST.has_voted || POST.has_voted.upvote != false"
                      class="ri-thumb-down-line ri-lg me-3"
                      @click="handlePostVote('down')"
                    ></i>
                    <i
                      v-else-if="POST.has_voted.upvote == false"
                      class="ri-thumb-down-fill ri-lg me-3"
                      @click="deletePostVote()"
                    ></i>

                    <span>{{ POST.downvote_count }}</span>
                  </p>
                </div>
              </div>
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
  data() {
    return {
      loading: false
    }
  },
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
      GET_POST: 'posts/GET_POST',
      CREATE_POST_VOTE: 'posts/CREATE_POST_VOTE',
      UPDATE_POST_VOTE: 'posts/UPDATE_POST_VOTE',
      DELETE_POST_VOTE: 'posts/DELETE_POST_VOTE'
    }),
    handlePostVote(direction) {
      const hasVoted = this.POST.has_voted
      switch (hasVoted) {
        case null:
          this.createPostVote(direction)
          break
        default:
          this.updatePostVote(direction)
          break
      }
    },
    async createPostVote(dir) {
      try {
        this.loading = true
        await this.CREATE_POST_VOTE({
          post_id: this.$route.params.id,
          upvote: dir == 'up' ? true : false
        })
      } catch (err) {
        this.$toast.error(err.response.statusText)
      } finally {
        this.loading = false
      }
    },
    async updatePostVote(dir) {
      try {
        this.loading = true
        await this.UPDATE_POST_VOTE({
          post_vote_id: this.POST.has_voted.id,
          upvote: dir == 'up' ? true : false
        })
      } catch (err) {
        this.$toast.error(err.response.statusText)
      } finally {
        this.loading = false
      }
    },
    async deletePostVote() {
      try {
        this.loading = true
        await this.DELETE_POST_VOTE(this.POST.has_voted)
      } catch (err) {
        this.$toast.error(err.response.statusText)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style>
.ri-thumb-up-line,
.ri-thumb-up-fill,
.ri-thumb-down-line,
.ri-thumb-down-fill {
  cursor: pointer;
}
</style>
