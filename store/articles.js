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
  },
  REFRESH_ARTICLE(state) {
    state.article = {}
  },
  LIKED_ARTICLE(state, payload) {
    state.article.data.has_liked = payload
    state.article.data.likes_count += 1
  },
  UNLIKED_ARTICLE(state) {
    state.article.data.has_liked = false
    state.article.data.likes_count -= 1
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
  },
  async UPDATE_ARTICLE({ commit }, { id, formData }) {
    const res = await this.$axios.$post(`articles/${id}?_method=PUT`, formData)
    commit('SET_ARTICLE', res)
  },
  async GET_ARTICLE({ commit }, { id, slug }) {
    const res = await this.$axios.$get(`articles/${id}/${slug}`)
    commit('SET_ARTICLE', res)
  },
  async LIKE_ARTICLE({ commit }, article_id) {
    const res = await this.$axios.$post('article-likes', {
      article_id: article_id
    })
    commit('LIKED_ARTICLE', res)
  },
  async UNLIKE_ARTICLE({ commit }, article_like_id) {
    await this.$axios.$delete(`article-likes/${article_like_id}`)
    commit('UNLIKED_ARTICLE')
  }
}
