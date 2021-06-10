<template>
  <div>
    <div class="row mt-3">
      <div class="col">
        <h1>Create Category</h1>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-12">
        <form @submit.prevent="submitCategory">
          <label for="categoryName" class="form-label">Name</label>
          <input
            id="categoryName"
            v-model="form.name"
            type="text"
            class="form-control"
            placeholder="Parent category name"
            required
          />
          <button type="submit" class="btn btn-success mt-3 text-white">
            Submit
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  name: 'CreateCategoriesManagement',
  data() {
    return {
      form: {
        name: '',
        parent_id: null
      }
    }
  },
  computed: {
    ...mapGetters({
      CATEGORIES: 'categories/CATEGORIES'
    })
  },
  methods: {
    ...mapActions({
      GET_CATEGORIES: 'categories/GET_CATEGORIES',
      CREATE_CATEGORY: 'categories/CREATE_CATEGORY',
      UPDATE_LOADING: 'UPDATE_LOADING'
    }),
    async submitCategory() {
      this.UPDATE_LOADING(true)
      try {
        await this.CREATE_CATEGORY(this.form)
        this.$toast.success('Success create category')
        await this.$delay(1000)
        this.$router.push('/dashboard/categories-management')
      } catch (err) {
        console.log(err)
        // this.$toast.error("Sorry! Something went wrong. Please try again later.")
      } finally {
        this.UPDATE_LOADING(false)
      }
    }
  }
}
</script>

<style></style>
