<template>
  <div>
    <div class="row mt-3">
      <div class="col">
        <h1>Update Category</h1>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-12">
        <form @submit.prevent="submitCategory('parent')">
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
            Update
          </button>
          <button
            type="button"
            class="btn btn-danger mt-3 text-white"
            @click="deleteCategory(CATEGORY.id, false)"
          >
            Delete
          </button>
        </form>
      </div>
    </div>
    <hr />
    <div class="row mt-3">
      <div class="col-12">
        <h1>Children</h1>
      </div>
      <div class="col-12 text-end">
        <button
          tag="button"
          class="btn btn-primary d-flex align-items-center float-end"
          data-bs-toggle="modal"
          data-bs-target="#modalChildrenForm"
        >
          <i class="ri-add-box-line me-2"></i> <span>New Child</span>
        </button>
      </div>
      <div class="col-12">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th class="row-title" scope="col">Name</th>
              <th class="text-end" scope="col">Actions</th>
            </tr>
          </thead>
          <tbody v-if="CATEGORY">
            <tr v-for="child in CATEGORY.children" :key="child.id">
              <th scope="row">{{ child.id }}</th>
              <td class="row-title">{{ child.name }}</td>
              <td class="text-end">
                <button
                  class="btn btn-primary me-2"
                  data-bs-toggle="modal"
                  data-bs-target="#modalChildrenForm"
                  @click="
                    childIsUpdate = true
                    selectedChild = child
                    initChildForm()
                  "
                >
                  Update</button
                ><button
                  class="btn btn-danger text-white"
                  @click="deleteCategory(child.id, true)"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div
      id="modalChildrenForm"
      class="modal fade"
      tabindex="-1"
      aria-labelledby="modalChildrenFormLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog text-dark">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="modalChildrenFormLabel" class="modal-title">Add Child</h5>
          </div>
          <form @submit.prevent="submitCategory">
            <div class="modal-body">
              <label for="childrenCategoryName" class="form-label">Name</label>
              <input
                id="childrenCategoryName"
                v-model="childForm.name"
                type="text"
                class="form-control"
                placeholder="Child category name"
              />
            </div>
            <div class="modal-footer">
              <button
                id="closeModal"
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal"
              >
                Close
              </button>
              <button type="submit" class="btn btn-primary">
                Submit
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
export default {
  name: 'ShowCategory',
  data() {
    return {
      form: {
        name: '',
        parent_id: 0
      },
      childForm: {
        name: '',
        parent_id: 0
      },
      childIsUpdate: false,
      selectedChild: {}
    }
  },
  computed: {
    ...mapGetters({
      CATEGORY: 'categories/CATEGORY'
    })
  },
  async mounted() {
    await this.GET_CATEGORY({ categoryId: this.$route.params.id })
    this.form.name = this.CATEGORY.name
    this.form.parent_id = this.CATEGORY.parent_id
    this.childForm.parent_id = this.CATEGORY.id
  },
  methods: {
    ...mapActions({
      GET_CATEGORY: 'categories/GET_CATEGORY',
      UPDATE_LOADING: 'UPDATE_LOADING',
      CREATE_CATEGORY: 'categories/CREATE_CATEGORY',
      UPDATE_CATEGORY: 'categories/UPDATE_CATEGORY',
      DELETE_CATEGORY: 'categories/DELETE_CATEGORY'
    }),
    async submitCategory(val = false) {
      this.UPDATE_LOADING(true)
      try {
        if (val == 'parent') {
          await this.UPDATE_CATEGORY({
            payload: this.form,
            categoryId: this.$route.params.id
          })
        } else if (this.childIsUpdate) {
          await this.UPDATE_CATEGORY({
            payload: this.childForm,
            categoryId: this.selectedChild.id
          })
        } else {
          await this.CREATE_CATEGORY(this.childForm)
        }
        this.$toast.success('Success! Data saved!')
        document.getElementById('closeModal').click()
        this.resetForm()
      } catch (err) {
        this.$toast.error('Something went wrong')
      } finally {
        this.UPDATE_LOADING(false)
      }
    },
    async deleteCategory(id, children = false) {
      if (confirm('are you sure you want to delete this category?')) {
        try {
          await this.DELETE_CATEGORY({ id: id, children: children })
          this.$toast.success('Success! Data deleted!')
          if (!children) {
            await this.$delay(1000)
            this.$router.push('/dashboard/categories-management/')
          }
        } catch (err) {
          console.log(err)
          this.$toast.error('Something went wrong')
        } finally {
          this.UPDATE_LOADING(false)
        }
      }
    },
    initChildForm() {
      this.childForm.name = this.selectedChild.name
      this.childForm.parent_id = this.selectedChild.parent_id
    },
    resetForm() {
      this.childForm = {
        name: '',
        parent_id: this.CATEGORY.id
      }
      this.childIsUpdate = false
      this.selectedChild = {}
    }
  }
}
</script>

<style></style>
