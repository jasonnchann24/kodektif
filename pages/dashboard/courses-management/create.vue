<template>
  <div>
    <div class="container">
      <div class="row my-2">
        <div class="col">
          <h1>Create New Course</h1>
        </div>
      </div>
      <div v-if="CATEGORIES.data">
        <div class="row">
          <div class="col-12 col-lg-6">
            <label for="courseTitle" class="form-label">Title</label>
            <input
              id="courseTitle"
              v-model="form.title"
              type="text"
              class="form-control"
              placeholder="Title for course"
            />
          </div>
          <div class="col-12 col-lg-6">
            <label for="courseSlug" class="form-label">Slug</label>
            <input
              id="courseSlug"
              v-model="form.slug"
              type="text"
              class="form-control"
              placeholder="Slug for course"
            />
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-12 col-lg-6">
            <label for="courseDescription" class="form-label"
              >Description</label
            >
            <textarea
              id="courseDescription"
              v-model="form.description"
              type="text"
              class="form-control"
              rows="6"
              placeholder="Description for course"
            />
          </div>
          <div class="col-12 col-lg-6">
            <label for="courseChapterCount" class="form-label"
              >Chapters Count</label
            >
            <input
              id="courseChapterCount"
              v-model="form.chapter_count"
              type="number"
              class="form-control"
              min="1"
            />
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-12 col-lg-8">
            <label for="courseCategories" class="form-label">Categories</label>
            <TreeSelect
              id="courseCategories"
              v-model="form.categories"
              :disable-branch-nodes="true"
              :multiple="true"
              :options="CATEGORIES.data"
              placeholder="Select categories..."
            />
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col">
        <button
          class="btn btn-success text-white float-end"
          type="button"
          @click="submitCourse"
        >
          Submit Course
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  name: 'CourseManagementCreate',
  data() {
    return {
      form: {
        categories: [],
        title: '',
        slug: '',
        description: '',
        chapter_count: 1
      }
    }
  },
  computed: {
    ...mapGetters({
      CATEGORIES: 'categories/CATEGORIES',
      COURSE: 'courses/COURSE'
    })
  },
  watch: {
    'form.title'() {
      this.form.slug = this.form.title
        .toString()
        .toLowerCase()
        .replace(/\s+/g, '-') // Replace spaces with -
        .replace(/[^\w-]+/g, '') // Remove all non-word chars
        .replace(/--+/g, '-') // Replace multiple - with single -
        .replace(/^-+/, '') // Trim - from start of text
        .replace(/-+$/, '')
    }
  },
  async mounted() {
    this.$toast.info('Loading resources')
    await this.GET_CATEGORIES()
    this.$toast.success('Loaded resources')
  },
  methods: {
    ...mapActions({
      GET_CATEGORIES: 'categories/GET_CATEGORIES',
      CREATE_COURSE: 'courses/CREATE_COURSE',
      UPDATE_LOADING: 'UPDATE_LOADING'
    }),
    async submitCourse() {
      if (!this.validateForm()) {
        return false
      }

      try {
        this.UPDATE_LOADING()
        await this.CREATE_COURSE(this.form)
        this.$toast.success('Course Created')
        await this.$delay(2000)
        this.$router.push(
          `/dashboard/courses-management/${this.COURSE.id}/edit`
        )
      } catch (err) {
        this.$toast.error(err.response.statusText)
      } finally {
        this.UPDATE_LOADING(false)
      }
    },
    validateForm() {
      for (const key in this.form) {
        if (!this.form[key]) {
          this.$toast.error('All fields must be filled.')
          return false
        }
      }

      return true
    }
  }
}
</script>

<style>
.vue-treeselect__menu {
  color: black;
}
</style>
