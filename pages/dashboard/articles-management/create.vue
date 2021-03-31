<template>
  <div class="container">
    <div class="row my-2">
      <div class="col">
        <h1>Create New Article</h1>
      </div>
    </div>
    <form
      v-if="CATEGORIES.data && LANGUAGES.data"
      @submit.prevent="submitArticle"
    >
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
        <div class="col-12 col-lg-6">
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
            <CoreEditor id="bodyContent" @update="updateBody" />
          </client-only>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col">
          <button class="btn btn-success text-white float-end" type="submit">
            Submit Article
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  name: 'CreateArticle',
  data() {
    return {
      dropzoneOptions: {
        url: 'uploadedWithArticleForm',
        thumbnailWidth: 300,
        maxFilesize: 0.5,
        maxFiles: 1,
        manuallyAddFile: true,
        autoProcessQueue: false,
        addRemoveLinks: true,
        dictDefaultMessage:
          '<i class="ri-upload-cloud-2-line ri-lg me-2"></i> Drop 1 image here to upload'
      },
      form: {
        title: '',
        description: '',
        language_id: null,
        categories: [],
        body: ''
      }
    }
  },
  computed: {
    ...mapGetters({
      LANGUAGES: 'languages/LANGUAGES',
      CATEGORIES: 'categories/CATEGORIES'
    })
  },
  async mounted() {
    this.UPDATE_LOADING(true)
    this.$toast.info('Loading required data')
    await this.GET_LANGUAGES()
    await this.GET_CATEGORIES()
    this.UPDATE_LOADING(false)
  },
  methods: {
    ...mapActions({
      GET_LANGUAGES: 'languages/GET_LANGUAGES',
      GET_CATEGORIES: 'categories/GET_CATEGORIES',
      UPDATE_LOADING: 'UPDATE_LOADING'
    }),
    fileAdded(file) {
      console.log(file)
      //this.form.append(file[this.counter], file)
    },
    fileExceeded(file) {
      this.$toast.error('Only one image allowed')
      this.$refs.myVueDropzone.removeFile(file)
    },
    updateBody(value) {
      this.form.body = value
    },
    submitArticle() {
      this.$toast('hh')
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
