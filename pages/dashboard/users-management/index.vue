<template>
  <div>
    <div class="row mt-3">
      <div class="col">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th class="row-title" scope="col">Name</th>
              <th class="row-title" scope="col">Email</th>
              <th scope="col">Role</th>
              <th scope="col">Suspended</th>
              <th class="text-center" scope="col">Actions</th>
            </tr>
          </thead>
          <tbody v-if="USERS.data">
            <tr v-for="user in USERS.data" :key="user.id">
              <th scope="row">{{ user.id }}</th>
              <td class="row-title">{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td>
                {{ getRoles(user.roles) }}
              </td>
              <td>{{ user.is_suspended ? 'true' : 'false' }}</td>
              <td>
                <div class="row">
                  <div
                    class="col d-flex align-items-center justify-content-evenly"
                  >
                    <button
                      class="btn btn-sm text-white"
                      :class="{
                        'btn-danger': !user.is_suspended,
                        'btn-success ': user.is_suspended
                      }"
                      @click="handleSuspend(user)"
                    >
                      <span v-if="user.is_suspended">UNSUSPEND</span
                      ><span v-else>SUSPEND</span>
                    </button>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div v-if="USERS.meta" class="row mt-3">
      <div class="col d-flex align-items-center justify-content-end">
        <BasePagination
          module="users"
          getter="USERS"
          action="GET_USERS"
          :total-pages="USERS.meta.last_page"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
export default {
  name: 'UsersManagementIndex',
  computed: {
    ...mapGetters({
      USERS: 'users/USERS'
    })
  },
  async mounted() {
    try {
      this.UPDATE_LOADING()
      this.GET_USERS({})
    } catch (err) {
      this.$toast.error(err.response.statusText)
    } finally {
      this.UPDATE_LOADING(false)
    }
  },
  methods: {
    ...mapActions({
      GET_USERS: 'users/GET_USERS',
      UPDATE_LOADING: 'UPDATE_LOADING',
      SUSPEND_USER: 'users/SUSPEND_USER',
      UNSUSPEND_USER: 'users/UNSUSPEND_USER'
    }),
    getRoles(roles) {
      if (roles.length < 1) {
        return '---'
      }

      return roles
        .map((x) => {
          return x.name
        })
        .join(', ')
    },
    async handleSuspend(user) {
      try {
        if (user.is_suspended == 0) {
          await this.SUSPEND_USER({ id: user.id })
        } else {
          await this.UNSUSPEND_USER(user.id)
        }
      } catch (err) {
        console.log(err)
      }
    }
  }
}
</script>

<style></style>
