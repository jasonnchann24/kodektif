export const state = () => ({
  articles: []
})

export const getters = {
  ARTICLES(state) {
    return state.articles
  }
}

export const mutations = {
  SET_ARTICLES(state, payload) {
    state.articles = payload
  }
}

export const actions = {
  async GET_ARTICLES({ commit }, { page = 1 }) {
    const res = await this.$axios.$get(`articles?page=${page}`)
    commit('SET_ARTICLES', res)
  }
}
