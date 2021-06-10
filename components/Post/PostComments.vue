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
        <div
          v-if="POST_COMMENTS.data && POST_COMMENTS.data.length > 0"
          class="col"
        >
          <div
            v-for="comment in POST_COMMENTS.data"
            :key="comment.id"
            class="card my-2 bg-transparent text-white border-0"
          >
            <div class="card-body py-0">
              <div class="row">
                <div class="col-12 col-md-3">
                  <div class="row d-flex flex-row align-items-start mt-3">
                    <div class="col-8 col-md-8">
                      <div class="row d-flex flex-column align-items-start">
                        <div class="col-4 col-md-6">
                          <img
                            v-if="comment.user.provider"
                            :src="comment.user.provider.avatar"
                            class="rounded-circle"
                            style="max-width:50px"
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
                    <div class="col-4 col-md-4  align-self-end">
                      <BaseVote module="postComments" :model="comment" />
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
                    :class="{
                      'disabled pe-none text-muted': comment.replies.length < 1
                    }"
                    @click="
                      currentReplies == comment.id
                        ? (currentReplies = 0)
                        : (currentReplies = comment.id)
                    "
                    ><span v-if="currentReplies != comment.id"
                      >View Replies</span
                    ><span v-else>Close Replies</span></a
                  >
                  <a
                    href="javascript:void(0);"
                    class="text-white me-2 me-md-4 me-lg-5"
                    :class="{ 'disabled pe-none text-muted': !isAuthenticated }"
                    @click="
                      targetedCommentId == comment.id
                        ? (targetedCommentId = 0)
                        : (targetedCommentId = comment.id)
                    "
                    ><span v-if="targetedCommentId != comment.id">Reply</span
                    ><span v-else>Cancel Reply</span></a
                  >
                </div>
                <div v-if="targetedCommentId == comment.id" class="col-12">
                  <CommentReplyForm
                    init-content="Reply comment..."
                    vuex-module="postComments"
                    vuex-action="CREATE_POST_COMMENT_REPLY"
                    :main-payload="replyPayload"
                    @submitted="targetedCommentId = 0"
                  />
                </div>
              </div>
              <div
                v-if="currentReplies === comment.id"
                class="row justify-content-end"
              >
                <div class="col-12 col-md-9 border-start border-info">
                  <CommentReplies
                    :replies="comment.replies"
                    vuex-module="postComments"
                    vuex-delete-action="DELETE_POST_COMMENT_REPLY"
                    parent-foreign-key="post_comment_id"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="col">
          <p class="text-danger">
            <span v-if="loading">Loading</span>
            <span v-else>No comment</span>
          </p>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col">
          <div v-if="POST_COMMENTS.meta" class="mt-3 ">
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
      currentReplies: 0,
      targetedCommentId: 0,
      loading: false,
      replyPayload: {}
    }
  },
  computed: {
    ...mapGetters({
      isAuthenticated: 'isAuthenticated',
      loggedInUser: 'loggedInUser',
      POST: 'posts/POST',
      POST_COMMENTS: 'postComments/POST_COMMENTS'
    })
  },
  watch: {
    targetedCommentId() {
      this.replyPayload = {
        post_comment_id: this.targetedCommentId
      }
    }
  },

  async created() {
    try {
      this.loading = true
      await this.GET_POST_COMMENTS({ post_id: this.$route.params.id })
    } catch (err) {
      this.$toast.error(err.response.statusText)
    } finally {
      this.loading = false
    }
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
  max-height: 500px;
  overflow: auto;
}
</style>
