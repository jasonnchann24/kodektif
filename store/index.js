export const state = () => ({})

export const getters = {
  isAuthenticated(state) {
    return state.auth.loggedIn
  },

  loggedInUser(state) {
    return state.auth.user
  }
}

export const mutations = {
  SET_USER_LOGGED_IN(state) {
    state.auth.loggedIn = true
  }
}

export const actions = {
  SUCCESSFUL_LOGIN({ commit }) {
    commit('SET_USER_LOGGED_IN')
  }
}
