export const state = () => ({
  post_comments: []
})

export const getters = {
  POST_COMMENTS(state) {
    return state.post_comments
  }
}

export const mutations = {
  SET_POST_COMMENTS(state, payload) {
    state.post_comments = payload
  },
  ADD_COMMENT(state, payload) {
    state.post_comments.data.unshift(payload.data)
  }
}

export const actions = {
  async GET_POST_COMMENTS({ commit }, { post_id = 0, page = 1 }) {
    const res = await this.$axios.$get(
      `post-comments?page=${page}&post_id=${post_id}`
    )
    commit('SET_POST_COMMENTS', res)
  },
  async CREATE_POST_COMMENT({ commit }, payload) {
    const res = await this.$axios.$post(`post-comments`, payload)
    commit('ADD_COMMENT', res)
  }
}
