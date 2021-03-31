export const state = () => ({
  articles: [],
  article: {}
})

export const getters = {
  ARTICLES(state) {
    return state.articles
  },
  ARTICLE(state) {
    return state.article
  }
}

export const mutations = {
  SET_ARTICLES(state, payload) {
    state.articles = payload
  },
  SET_ARTICLE(state, payload) {
    state.article = payload
  }
}

export const actions = {
  async GET_ARTICLES({ commit }, { page = 1 }) {
    const res = await this.$axios.$get(`articles?page=${page}`)
    commit('SET_ARTICLES', res)
  },
  async CREATE_ARTICLE({ commit }, formData) {
    const res = await this.$axios.$post(`articles`, formData)
    commit('SET_ARTICLE', res)
  }
}
