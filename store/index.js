export const state = () => ({
  loading: false
})

export const getters = {
  isAuthenticated(state) {
    return state.auth.loggedIn
  },
  isLoading(state) {
    return state.loading
  },

  loggedInUser(state) {
    return state.auth.user
  }
}

export const mutations = {
  SET_USER_LOGGED_IN(state) {
    state.auth.loggedIn = true
  },
  SET_LOADING(state, bool) {
    state.loading = bool
  }
}

export const actions = {
  SUCCESSFUL_LOGIN({ commit }) {
    commit('SET_USER_LOGGED_IN')
  },
  async nuxtServerInit({ commit }, { req }) {
    let res = null
    if (req.headers.cookie) {
      try {
        res = await this.$axios.$get('/user')
        await this.$auth.setUser(res.data)
      } catch (err) {
        console.log(err)
      }
    }

    if (res) {
      commit('SET_USER_LOGGED_IN')
    }
  },
  UPDATE_LOADING({ commit }, bool) {
    commit('SET_LOADING', bool)
  }
}
