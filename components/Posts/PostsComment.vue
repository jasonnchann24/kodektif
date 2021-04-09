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
            v-for="comment in POST_COMMENTS.data"
            :key="comment.id"
            class="card my-2 bg-transparent text-white border-0"
          >
            <div class="card-body py-0">
              <div class="row">
                <div class="col-12 col-md-3">
                  <div class="row d-flex flex-column align-items-center">
                    <div class="col">
                      <img
                        :src="comment.user.provider.avatar"
                        class="rounded-circle d-none d-md-block"
                        style="width: 25%"
                        alt="comment avatar"
                      />
                    </div>
                    <div class="col">
                      <small class="text-small mb-0"
                        >by {{ comment.user.name }}</small
                      >
                      <small class="text-small text-muted mb-0">
                        {{
                          $moment(comment.created_at).format(
                            'dddd, DD MMM YYYY'
                          )
                        }}
                      </small>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-9">
                  <client-only>
                    <CoreHtmlViewer :body-content="comment.body" />
                  </client-only>
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
  name: 'PostsComment',
  data() {
    return {
      form: {
        body: '',
        post_id: this.$route.params.id
      },
      additionalParams: {
        post_id: this.$route.params.id
      }
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

<style></style>
