export const state = () => ({
  users: [],
  user: {}
})

export const getters = {
  USERS(state) {
    return state.users
  },
  USER(state) {
    return state.user
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
  },
  SET_USER(state, payload) {
    state.user = payload
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
  },
  async GET_USER({ commit, state }, id) {
    if (state.user.data && state.user.data.id == id) {
      return
    }
    const res = await this.$axios.$get(`users/${id}`)
    commit('SET_USER', res)
  }
}
