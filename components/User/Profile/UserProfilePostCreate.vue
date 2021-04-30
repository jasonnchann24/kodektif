<template>
  <div>
    <div v-if="loading"><p>Loading resourcess...</p></div>
    <client-only>
      <div v-if="CATEGORIES.data && LANGUAGES.data" class="col-12">
        <div class="row">
          <div class="col-12 col-lg-6">
            <label for="postTitle" class="form-label">Title</label>
            <input
              id="postTitle"
              v-model="form.title"
              type="text"
              class="form-control"
              placeholder="Title for post"
            />
          </div>
          <div class="col-12 mt-3 mt-lg-0 col-lg-6">
            <label for="postLanguage" class="form-label">Language</label>
            <TreeSelect
              v-model="form.language_id"
              :disable-branch-nodes="true"
              :multiple="false"
              :options="LANGUAGES.data"
              placeholder="Select language..."
            />
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-12 col-lg-4">
            <label for="postCategories" class="form-label">Categories</label>
            <TreeSelect
              id="postCategories"
              v-model="form.categories"
              :disable-branch-nodes="true"
              :multiple="true"
              :options="CATEGORIES.data"
              placeholder="Select categories..."
            />
          </div>
          <div class="col-12 col-lg-8">
            <label for="postDescription" class="form-label">Description</label>
            <textarea
              id="postDescription"
              v-model="form.description"
              type="text"
              class="form-control"
              rows="1"
              placeholder="Description for post"
            />
          </div>
        </div>
      </div>
      <div class="col-12 mt-3">
        <CoreEditor @update="updateBody" />
      </div>
      <div class="col-12 mt-3 d-grid d-block">
        <button
          class="btn btn-block btn-outline-success text-white"
          @click="createPost"
        >
          Submit Post
        </button>
      </div>
    </client-only>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
export default {
  name: 'UserProfilePostCreate',
  data() {
    return {
      form: {
        title: '',
        description: '',
        language_id: null,
        categories: [],
        body: ''
      },
      loading: false
    }
  },
  computed: {
    ...mapGetters({
      CATEGORIES: 'categories/CATEGORIES',
      LANGUAGES: 'languages/LANGUAGES'
    })
  },
  async created() {
    this.loading = true
    await Promise.all([this.GET_CATEGORIES(), this.GET_LANGUAGES()])
    this.loading = false
  },
  methods: {
    ...mapActions({
      GET_CATEGORIES: 'categories/GET_CATEGORIES',
      GET_LANGUAGES: 'languages/GET_LANGUAGES',
      CREATE_POST: 'posts/CREATE_POST',
      UPDATE_LOADING: 'UPDATE_LOADING'
    }),
    updateBody(value) {
      this.form.body = value
    },
    async createPost() {
      try {
        this.UPDATE_LOADING()
        await this.CREATE_POST(this.form)
        this.form = {
          title: '',
          description: '',
          language_id: null,
          categories: [],
          body: ''
        }
        this.$toast.success('Post created')
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
