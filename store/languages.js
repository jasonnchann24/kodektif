export const state = () => ({
  languages: []
})

export const getters = {
  LANGUAGES(state) {
    return state.languages
  }
}

export const mutations = {
  SET_LANGUAGES(state, payload) {
    state.languages = payload
  }
}

export const actions = {
  async GET_LANGUAGES({ commit }) {
    const res = await this.$axios.$get(`languages`)
    commit('SET_LANGUAGES', res)
  }
}
