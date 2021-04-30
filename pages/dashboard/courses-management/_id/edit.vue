<template>
  <div v-if="CATEGORIES.data && COURSE">
    <div class="container">
      <div class="row my-2">
        <div class="col">
          <h1>
            Edit Course for <u>{{ COURSE.title }}</u>
          </h1>
        </div>
      </div>
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
          <label for="courseDescription" class="form-label">Description</label>
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
      <div class="row mt-3">
        <div class="col-12 text-end">
          <button
            type="button"
            class="btn btn-danger text-white "
            @click="deleteCourse"
          >
            Delete Course
          </button>
          <button
            type="button"
            class="btn btn-success text-white ms-3"
            @click="updateCourse"
          >
            Update Course
          </button>
        </div>
      </div>
    </div>
    <hr class="my-5" />
    <DashboardChapterSection />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
export default {
  name: 'CourseManagementEdit',
  middleware: ['onlyAdmin'],

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
      COURSE: 'courses/COURSE',
      CATEGORIES: 'categories/CATEGORIES',
      isLoading: 'isLoading'
    })
  },
  watch: {
    'form.title'() {
      this.form.slug = this.$slugify(this.form.title)
    }
  },

  async mounted() {
    this.UPDATE_LOADING()
    await this.GET_COURSE(this.$route.params.id)
    await this.GET_CATEGORIES()
    this.initData()
    this.UPDATE_LOADING(false)
  },
  methods: {
    ...mapActions({
      UPDATE_LOADING: 'UPDATE_LOADING',
      GET_CATEGORIES: 'categories/GET_CATEGORIES',
      GET_COURSE: 'courses/GET_COURSE',
      UPDATE_COURSE: 'courses/UPDATE_COURSE',
      DELETE_COURSE: 'courses/DELETE_COURSE'
    }),

    initData() {
      this.form.categories = this.COURSE.categories.map((x) => x.id)
      this.form.title = this.COURSE.title
      this.form.slug = this.COURSE.slug
      this.form.description = this.COURSE.description
      this.form.chapter_count = this.COURSE.chapter_count
    },
    async updateCourse() {
      try {
        this.UPDATE_LOADING()
        await this.UPDATE_COURSE({
          id: this.$route.params.id,
          payload: this.form
        })
        this.$toast.success('Course updated')
      } catch (err) {
        this.$toast.error(err.response.statusText)
      } finally {
        this.UPDATE_LOADING(false)
      }
    },
    async deleteCourse() {
      if (confirm('are you sure you want to delete this course?')) {
        try {
          this.UPDATE_LOADING()
          await this.DELETE_COURSE(this.$route.params.id)
          this.$toast.success('Course Deleted')
          await this.$delay(1500)
          this.$router.push('/dashboard/courses-management')
        } catch (err) {
          this.$toast.error(err.response.statusText)
        } finally {
          this.UPDATE_LOADING(false)
        }
      }
    }
  }
}
</script>

<style></style>
