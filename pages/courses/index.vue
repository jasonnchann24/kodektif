<template>
  <div>
    <div class="container-fluid">
      <div class="row">
        <div class="col text-center">
          <img
            src="@/assets/svgs/online_learning.svg"
            alt="course image banner"
            srcset=""
            style="max-width: 100%"
          />
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row mt-4">
        <div class="col">
          <h1>Courses</h1>
        </div>
      </div>
      <div class="row mt-3 g-3">
        <div
          v-for="course in COURSES.data"
          :key="course.id"
          class="col-12 col-md-6 d-flex justify-content-center"
        >
          <div class="card bg-primary w-100">
            <div class="card-body">
              <h3 class="card-title">{{ course.title }}</h3>
              <h6 class="card-subtitle mb-2 text-warning">
                <span
                  v-for="(category, index) in course.categories"
                  :key="index"
                  >{{ category.label
                  }}<span v-if="index != course.categories.length - 1">, </span>
                </span>
              </h6>
              <p class="card-text description-course" style="height: 3rem;">
                {{ course.description }}
              </p>
              <NuxtLink
                :to="
                  `/courses/${course.id}/${course.slug}/${course.chapters[0].slug}`
                "
                class="text-white"
                >Take Course</NuxtLink
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  name: 'CoursesIndex',
  async fetch() {
    this.UPDATE_LOADING()
    await this.GET_COURSES()
    this.UPDATE_LOADING(false)
  },
  computed: {
    ...mapGetters({
      COURSES: 'courses/COURSES'
    })
  },
  methods: {
    ...mapActions({
      GET_COURSES: 'courses/GET_COURSES',
      UPDATE_LOADING: 'UPDATE_LOADING'
    })
  }
}
</script>

<style scoped>
.description-course {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
