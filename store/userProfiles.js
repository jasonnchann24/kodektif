export const state = () => ({
  user_profile: {}
})

export const getters = {
  USER_PROFILE(state) {
    return state.user_profile
  }
}

export const mutations = {
  SET_USER_PROFILE(state, payload) {
    state.user_profile = payload
  }
}

export const actions = {
  async GET_USER_PROFILE({ commit, state }, id) {
    if (state.user_profile.data) {
      return
    }
    const res = await this.$axios.$get(`user-profiles/${id}`)
    commit('SET_USER_PROFILE', res)
  }
}
