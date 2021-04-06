export const state = () => ({
  users: []
})

export const getters = {
  USERS(state) {
    return state.users
  }
}

export const mutations = {
  SET_USERS(state, payload) {
    state.users = payload
  },
  SUSPEND_USER(state, id) {
    const user = state.users.data.find((x) => {
      return x.id == id
    })

    user.is_suspended = 1
  },
  UNSUSPEND_USER(state, id) {
    const user = state.users.data.find((x) => {
      return x.id == id
    })

    user.is_suspended = 0
  }
}

export const actions = {
  async GET_USERS({ commit, state }, { page = 1 }) {
    if (state.users.data && state.users.meta.current_page == page) {
      return
    }
    const res = await this.$axios.$get(`users?page=${page}`)
    commit('SET_USERS', res)
  },
  async SUSPEND_USER({ commit }, payload) {
    await this.$axios.$post('suspend-user', payload)
    commit('SUSPEND_USER', payload.id)
  },
  async UNSUSPEND_USER({ commit }, id) {
    await this.$axios.$delete('unsuspend-user/' + id)
    commit('UNSUSPEND_USER', id)
  }
}
