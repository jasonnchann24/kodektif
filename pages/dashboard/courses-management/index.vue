<template>
  <div>
    <div class="row">
      <div class="col">
        <nuxt-link
          to="/dashboard/courses-management/create"
          tag="button"
          class="btn btn-primary d-flex align-items-center float-end"
        >
          <i class="ri-add-box-line me-2"></i> <span>New Course</span>
        </nuxt-link>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th class="row-title" scope="col">Title</th>
              <th scope="col">Created at</th>
              <th scope="col">Created by</th>
              <th class="text-center" scope="col">Actions</th>
            </tr>
          </thead>
          <tbody v-if="COURSES.data">
            <tr v-for="course in COURSES.data" :key="course.id">
              <th scope="row">{{ course.id }}</th>
              <td class="row-title">{{ course.title }}</td>
              <td>{{ $moment(course.created_at).format('DD MMM YYYY') }}</td>
              <td>{{ course.user.name }}</td>
              <td>
                <div class="row">
                  <div
                    class="col d-flex align-items-center justify-content-evenly"
                  >
                    <!-- <NuxtLink
                      :to="`/articles/${article.id}/${article.slug}`"
                      class="btn btn-info text-white"
                    >
                      View
                    </NuxtLink> -->
                    <NuxtLink
                      :to="`/dashboard/courses-management/${course.id}/edit`"
                      class="btn btn-success text-white"
                    >
                      Update
                    </NuxtLink>
                    <!-- <button
                      class="btn btn-danger text-white"
                      @click="deleteArticle(article.id)"
                    >
                      Delete
                    </button> -->
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  name: 'CourseManagementIndex',
  computed: {
    ...mapGetters({
      COURSES: 'courses/COURSES'
    })
  },
  async mounted() {
    this.UPDATE_LOADING()
    await this.GET_COURSES()
    this.UPDATE_LOADING(false)
  },
  methods: {
    ...mapActions({
      GET_COURSES: 'courses/GET_COURSES',
      UPDATE_LOADING: 'UPDATE_LOADING'
    })
  }
}
</script>

<style></style>
