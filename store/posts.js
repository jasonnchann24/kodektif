export const state = () => ({
  posts: [],
  post: {}
})

export const getters = {
  POSTS(state) {
    return state.posts
  },
  POST(state) {
    return state.post
  }
}

export const mutations = {
  SET_POSTS(state, payload) {
    state.posts = payload
  },
  SET_POST(state, payload) {
    state.post = payload
  },
  CREATE_POST(state, payload) {
    state.posts.data.unshift(payload)
  }
}

export const actions = {
  async GET_POSTS({ commit }, { page = 1, user_id = '' }) {
    let url = `posts?page=${page}`
    if (user_id) {
      url += `&user_id=${user_id}`
    }
    const res = await this.$axios.$get(url)
    commit('SET_POSTS', res)
  },
  async CREATE_POST({ commit }, payload) {
    const res = await this.$axios.$post('posts', payload)
    commit('SET_POST', res)
    commit('CREATE_POST', res.data)
  }
}
