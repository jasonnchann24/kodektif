<template>
  <div>
    <div class="row mt-3">
      <div class="col text-end">
        <nuxt-link
          to="/dashboard/categories-management/create"
          tag="button"
          class="btn btn-primary d-flex align-items-center float-end"
        >
          <i class="ri-add-box-line me-2"></i> <span>New Parent Category</span>
        </nuxt-link>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th class="row-title" scope="col">Parent Category Name</th>
              <th class="text-end" scope="col">Actions</th>
            </tr>
          </thead>
          <tbody v-if="CATEGORIES.data">
            <tr v-for="category in CATEGORIES.data" :key="category.id">
              <th scope="row">{{ category.id }}</th>
              <td class="row-title">{{ category.name }}</td>
              <td class="text-end">
                <NuxtLink
                  :to="`/dashboard/categories-management/${category.id}`"
                  class="btn btn-primary"
                >
                  View
                </NuxtLink>
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
  name: 'IndexCategoryManagement',
  computed: {
    ...mapGetters({
      CATEGORIES: 'categories/CATEGORIES'
    })
  },
  mounted() {
    this.GET_CATEGORIES()
  },
  methods: {
    ...mapActions({
      GET_CATEGORIES: 'categories/GET_CATEGORIES',
      CREATE_CATEGORY: 'categories/CREATE_CATEGORY'
    })
  }
}
</script>

<style></style>
