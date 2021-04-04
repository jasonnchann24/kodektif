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
    <div class="container">
      <div class="row">
        <div class="col">
          <h1>Course Chapters</h1>
        </div>
      </div>
      <div class="row">
        <div class="col text-end">
          <button
            type="button"
            class="btn btn-primary d-flex align-items-center float-end"
            data-bs-toggle="modal"
            data-bs-target="#modalAddChapter"
          >
            <i class="ri-add-box-line me-2"></i> <span>Add new chapter</span>
          </button>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">Order</th>
                <th scope="col">Title</th>
                <th scope="col">Slug</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <template v-if="COURSE.chapters.length > 0">
                <tr v-for="chapter in COURSE.chapters" :key="chapter.id">
                  <th scope="row">{{ chapter.order }}</th>
                  <td>{{ chapter.title }}</td>
                  <td>{{ chapter.slug }}</td>
                  <td>
                    <button
                      type="button"
                      class="btn btn-success text-white"
                      data-bs-toggle="modal"
                      data-bs-target="#modalAddChapter"
                      @click="initUpdateChapter(chapter)"
                    >
                      Edit
                    </button>
                    <button
                      type="button"
                      class="btn btn-danger text-white"
                      @click="deleteChapter(chapter.id)"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              </template>
              <template v-else>
                <tr>
                  <th colspan="4" class="text-center">
                    <span class="text-danger">No chapters found</span>
                  </th>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div
      id="modalAddChapter"
      class="modal fade"
      tabindex="-1"
      aria-labelledby="modalAddChapterLabel"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="modalAddChapterLabel" class="modal-title text-dark">
              <span v-if="!edit_chapter">Add New</span
              ><span v-else>Edit</span> Chapter
            </h5>
          </div>
          <div class="modal-body">
            <div class="container">
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label for="chapterTitle" class="form-label text-dark"
                      >Title</label
                    >
                    <input
                      id="chapterTitle"
                      v-model="form_chapter.title"
                      type="text"
                      class="form-control"
                    />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label for="chapterSlug" class="form-label text-dark"
                      >Slug</label
                    >
                    <input
                      id="chapterSlug"
                      v-model="form_chapter.slug"
                      type="text"
                      class="form-control"
                    />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label for="chapterOrder" class="form-label text-dark"
                      >Order</label
                    >
                    <input
                      id="chapterOrder"
                      v-model="form_chapter.order"
                      type="number"
                      class="form-control"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              id="closeAddChapter"
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
              @click="edit_chapter = false"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              :class="{ disabled: isLoading }"
              :disable="isLoading"
              @click="saveChapter"
            >
              Save changes
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex'
export default {
  name: 'CourseManagementEdit',
  data() {
    return {
      form: {
        categories: [],
        title: '',
        slug: '',
        description: '',
        chapter_count: 1
      },
      form_chapter: {
        course_id: this.$route.params.id,
        title: '',
        slug: '',
        order: 0
      },
      edit_chapter: false
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
    },
    'form_chapter.title'() {
      this.form_chapter.slug = this.$slugify(this.form_chapter.title)
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
      DELETE_COURSE: 'courses/DELETE_COURSE',
      CREATE_CHAPTER: 'chapters/CREATE_CHAPTER',
      UPDATE_CHAPTER: 'chapters/UPDATE_CHAPTER',
      DELETE_CHAPTER: 'chapters/DELETE_CHAPTER'
    }),
    ...mapMutations({
      REMOVE_CHAPTER_FROM_COURSE: 'courses/REMOVE_CHAPTER_FROM_COURSE'
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
          payload: this.form_chapter
        })
        this.$toast.success('Course updated')
      } catch (err) {
        this.$toast.error(err.response.statusText)
      } finally {
        this.UPDATE_LOADING(false)
      }
    },
    saveChapter() {
      if (this.edit_chapter) {
        this.updateChapter()
      } else {
        this.createChapter()
      }
    },
    async updateChapter() {
      try {
        this.UPDATE_LOADING()
        this.$toast.info('Updating Chapter...')
        await this.UPDATE_CHAPTER({
          id: this.form_chapter.id,
          payload: this.form_chapter
        })
        this.$toast.success('Updated Chapter ...')
        this.resetFormChapter()
      } catch (err) {
        this.$toast.error(err.response.statusText)
      } finally {
        this.UPDATE_LOADING(false)
      }
    },
    async createChapter() {
      try {
        this.UPDATE_LOADING()
        this.$toast.info('Saving ...')
        await this.CREATE_CHAPTER(this.form_chapter)
        await this.GET_COURSE(this.$route.params.id)
        this.$toast.success('Chapter Added.')
        this.resetFormChapter()
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
    },
    async deleteChapter(id) {
      if (confirm('are you sure you want to delete this chapter?')) {
        try {
          this.UPDATE_LOADING()
          await this.DELETE_CHAPTER(id)
          this.$toast.success('Chapter deleted')
          this.REMOVE_CHAPTER_FROM_COURSE(id)
        } catch (err) {
          console.log(err)
          this.$toast.error(err.response.statusText)
        } finally {
          this.UPDATE_LOADING(false)
        }
      }
    },
    async initUpdateChapter(chapter) {
      this.edit_chapter = true
      this.form_chapter.id = chapter.id
      this.form_chapter.title = chapter.title
      this.form_chapter.slug = chapter.slug
      this.form_chapter.order = chapter.order
    },
    resetFormChapter() {
      document.getElementById('closeAddChapter').click()
      this.form_chapter = {
        course_id: this.$route.params.id,
        title: '',
        slug: '',
        order: 0
      }
      this.edit_chapter = false
    }
  }
}
</script>

<style></style>
