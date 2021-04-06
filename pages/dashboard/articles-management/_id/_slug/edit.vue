<template>
  <div v-if="allSet" class="container">
    <div class="row my-2">
      <div class="col">
        <h1>Update Article</h1>
      </div>
    </div>
    <div v-if="CATEGORIES.data && LANGUAGES.data">
      <div class="row">
        <div class="col-12 col-lg-6">
          <label for="articleTitle" class="form-label">Title</label>
          <input
            id="articleTitle"
            v-model="form.title"
            type="text"
            class="form-control"
            placeholder="Title for article"
          />
        </div>
        <div class="col-12 mt-3 mt-lg-0 col-lg-6">
          <label for="articleLanguage" class="form-label">Language</label>
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
        <div class="col-12 col-lg-8">
          <label for="articleCategories" class="form-label">Categories</label>
          <TreeSelect
            id="articleCategories"
            v-model="form.categories"
            :disable-branch-nodes="true"
            :multiple="true"
            :options="CATEGORIES.data"
            placeholder="Select categories..."
          />
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-12">
          <label for="articleDescription" class="form-label">Description</label>
          <textarea
            id="articleDescription"
            v-model="form.description"
            type="text"
            class="form-control"
            rows="6"
            placeholder="Description for article"
          />
        </div>
      </div>
      <div class="row mt-3">
        <div v-if="ARTICLE.data.article_image" class="col-12 col-lg-6">
          <p>Old Main Image</p>
          <img
            id="oldImage"
            :src="`${this.$config.BACKEND_URL}${ARTICLE.data.article_image}`"
            alt=""
          />
        </div>
        <div class="col-12 mt-3 mt-lg-0 col-lg-6">
          <label for="dropzone" class="form-label">Main Image</label>
          <client-only>
            <div class="text-center">
              <Vue2Dropzone
                id="dropzone"
                ref="myVueDropzone"
                :options="dropzoneOptions"
                @vdropzone-file-added="fileAdded"
                @vdropzone-max-files-exceeded="fileExceeded"
              />
            </div>
          </client-only>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col">
          <label for="bodyContent" class="form-label">Body Content</label>
          <client-only>
            <CoreEditor
              id="bodyContent"
              :init-content="ARTICLE.data.body"
              @update="updateBody"
            />
          </client-only>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col">
          <button
            class="btn btn-success text-white float-end"
            type="button"
            @click="updateArticle"
          >
            Update Article
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  name: 'EditArticle',
  middleware: ['onlyAdmin'],

  data() {
    return {
      dropzoneOptions: {
        url: 'uploadedWithArticleForm',
        thumbnailWidth: 300,
        maxFilesize: 1,
        maxFiles: 1,
        manuallyAddFile: true,
        autoProcessQueue: false,
        addRemoveLinks: true,
        dictDefaultMessage:
          '<i class="ri-upload-cloud-2-line ri-lg me-2"></i> Drop 1 image here to upload<br /><span class="text-danger">Max file size 1MB</span>'
      },
      form: {
        title: '',
        description: '',
        language_id: null,
        categories: [],
        body: ''
      },
      image: null
    }
  },
  computed: {
    ...mapGetters({
      LANGUAGES: 'languages/LANGUAGES',
      CATEGORIES: 'categories/CATEGORIES',
      ARTICLE: 'articles/ARTICLE'
    }),
    allSet() {
      if (Object.keys(this.ARTICLE).length > 0 && this.ARTICLE.data) {
        return this.ARTICLE.data.id == this.$route.params.id
      } else {
        return false
      }
    }
  },
  async mounted() {
    this.UPDATE_LOADING(true)
    this.$toast.info('Loading required data')
    await this.GET_ARTICLE({
      id: this.$route.params.id,
      slug: this.$route.params.slug
    })
    await this.GET_LANGUAGES()
    await this.GET_CATEGORIES()
    this.UPDATE_LOADING(false)
    this.initData()
  },
  methods: {
    ...mapActions({
      GET_LANGUAGES: 'languages/GET_LANGUAGES',
      GET_CATEGORIES: 'categories/GET_CATEGORIES',
      UPDATE_LOADING: 'UPDATE_LOADING',
      GET_ARTICLE: 'articles/GET_ARTICLE',
      UPDATE_ARTICLE: 'articles/UPDATE_ARTICLE'
    }),
    fileAdded(file) {
      if (file.size > 1000000) {
        this.$toast.error('File size is larger than 1MB')
        this.$refs.myVueDropzone.removeFile(file)
      }
      this.image = file
      //this.form.append(file[this.counter], file)
    },
    fileExceeded(file) {
      this.$toast.error('Only one image allowed')
      this.$refs.myVueDropzone.removeFile(file)
    },
    updateBody(value) {
      this.form.body = value
    },
    async updateArticle() {
      const formData = new FormData()

      for (const [key, value] of Object.entries(this.form)) {
        if (key == 'categories') {
          this.form.categories.forEach((x) => {
            formData.append('categories[]', x)
          })
        } else {
          formData.append(`${key}`, value)
        }
      }

      if (this.image != null) {
        formData.append('image', this.image)
      }

      this.UPDATE_LOADING(true)
      try {
        await this.UPDATE_ARTICLE({
          id: this.$route.params.id,
          formData: formData
        })
        this.$toast.success('Success update article')
        await this.$delay(2000)
        this.$router.push('/dashboard/articles-management')
      } catch (err) {
        this.$toast.error(err.response.statusText)
      } finally {
        this.UPDATE_LOADING(false)
      }
    },
    initData() {
      const article = this.ARTICLE.data
      this.form.title = article.title
      this.form.language_id = article.language.id
      this.form.categories = article.categories.map((x) => {
        return x.id
      })
      this.form.description = article.description
      this.form.body = article.body
    }
  }
}
</script>

<style>
.dz-progress {
  display: none !important;
}

.vue-treeselect__menu {
  color: black;
}
</style>
