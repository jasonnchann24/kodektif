<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col">
          <h4>Comments</h4>
        </div>
      </div>
      <div v-if="isAuthenticated" class="row mt-3">
        <div class="col">
          <client-only>
            <CoreEditor
              id="bodyContent"
              init-content="Comment this post here ..."
              @update="updateBody"
            />
          </client-only>
          <button
            class="btn btn-success text-white mt-3"
            @click="submitComment"
          >
            Submit
          </button>
        </div>
      </div>
      <div v-else class="row mt-3">
        <div class="col">
          <p class="text-danger">Login to comment this post</p>
        </div>
      </div>
      <div class="row mt-3">
        <div v-if="POST_COMMENTS.data" class="col">
          <div
            v-for="comment in filteredPostComment"
            :key="comment.id"
            class="card my-2 bg-transparent text-white border-0"
          >
            <div class="card-body py-0">
              <div class="row">
                <div class="col-12 col-md-3">
                  <div class="row d-flex flex-column align-items-start">
                    <div class="col-4 col-md-6">
                      <img
                        :src="comment.user.provider.avatar"
                        class="rounded-circle"
                        style="width:75%"
                        alt="comment avatar"
                      />
                    </div>
                    <div class="col-12 mt-3">
                      <p class="mb-0">
                        <small class="text-small mb-0"
                          >by {{ comment.user.name }}</small
                        >
                      </p>
                      <p class="mb-0">
                        <small class="text-small text-muted mb-0">
                          {{
                            $moment(comment.created_at).format(
                              'dddd, DD MMM YYYY'
                            )
                          }}
                        </small>
                      </p>
                    </div>
                  </div>
                </div>
                <div
                  class="col-12 col-md-9 my-2 max-comment-height align-self-end"
                >
                  <client-only>
                    <CoreHtmlViewer :body-content="comment.body" />
                  </client-only>
                </div>
              </div>

              <div class="row">
                <div class="col-12 text-end">
                  <a
                    href="javascript:void(0);"
                    class="text-white me-3"
                    @click="
                      currentReplies == comment.id
                        ? (currentReplies = 0)
                        : (currentReplies = comment.id)
                    "
                    >View Replies</a
                  >
                  <a
                    href="javascript:void(0);"
                    class="text-white me-5"
                    @click="
                      currentReplies == comment.id
                        ? (currentReplies = 0)
                        : (currentReplies = comment.id)
                    "
                    >Reply</a
                  >
                  <p v-if="currentReplies == comment.id">
                    test
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="col">
          <p class="text-danger">No comment</p>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col">
          <div v-if="POST_COMMENTS.meta && currentReplies == 0" class="mt-3 ">
            <BasePagination
              module="postComments"
              getter="POST_COMMENTS"
              action="GET_POST_COMMENTS"
              :total-pages="POST_COMMENTS.meta.last_page"
              :additional-params="additionalParams"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
export default {
  name: 'PostComments',
  data() {
    return {
      form: {
        body: '',
        post_id: this.$route.params.id
      },
      additionalParams: {
        post_id: this.$route.params.id
      },
      currentReplies: 0
    }
  },

  computed: {
    ...mapGetters({
      isAuthenticated: 'isAuthenticated',
      loggedInUser: 'loggedInUser',
      POST: 'posts/POST',
      POST_COMMENTS: 'postComments/POST_COMMENTS'
    }),
    filteredPostComment() {
      if (this.currentReplies == 0) {
        return this.POST_COMMENTS.data
      }
      return this.POST_COMMENTS.data.filter((x) => x.id == this.currentReplies)
    }
  },
  created() {
    this.GET_POST_COMMENTS({ post_id: this.$route.params.id })
  },
  methods: {
    ...mapActions({
      CREATE_POST_COMMENT: 'postComments/CREATE_POST_COMMENT',
      GET_POST_COMMENTS: 'postComments/GET_POST_COMMENTS',
      UPDATE_LOADING: 'UPDATE_LOADING'
    }),
    updateBody(value) {
      this.form.body = value
    },
    async submitComment() {
      try {
        this.UPDATE_LOADING()
        await this.CREATE_POST_COMMENT(this.form)
      } catch (err) {
        this.$toast.error(err.response.statusText)
      } finally {
        this.UPDATE_LOADING(false)
      }
    }
  }
}
</script>

<style>
.max-comment-height {
  max-height: 300px;
  overflow: auto;
}
</style>
