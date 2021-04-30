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
  },
  CREATED_POST_VOTE(state, payload) {
    state.post.has_voted = payload.data
    payload.data.upvote
      ? (state.post.upvote_count += 1)
      : (state.post.downvote_count += 1)
  },
  UPDATED_POST_VOTE(state, payload) {
    state.post.has_voted = payload.data
    payload.data.upvote
      ? (state.post.downvote_count -= 1)((state.post.upvote_count += 1))
      : (state.post.downvote_count += 1)((state.post.upvote_count -= 1))
  },
  DELETED_POST_VOTE(state, payload) {
    state.post.has_voted = null
    payload ? (state.post.upvote_count -= 1) : (state.post.downvote_count -= 1)
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
  async GET_POST({ commit }, { id, slug }) {
    const res = await this.$axios.$get(`posts/${id}/${slug}`)
    commit('SET_POST', res.data)
  },
  async CREATE_POST({ commit }, payload) {
    const res = await this.$axios.$post('posts', payload)
    commit('SET_POST', res)
    commit('CREATE_POST', res.data)
  },
  async CREATE_POST_VOTE({ commit }, payload) {
    const res = await this.$axios.$post('post-votes', payload)
    commit('CREATED_POST_VOTE', res)
  },
  async UPDATE_POST_VOTE({ commit }, payload) {
    const res = await this.$axios.$patch(`post-votes/${payload.post_vote_id}`, {
      upvote: payload.upvote
    })
    commit('UPDATED_POST_VOTE', res)
  },
  async DELETE_POST_VOTE({ commit }, payload) {
    const dir = payload.upvote
    await this.$axios.$delete(`post-votes/${payload.id}`)
    commit('DELETED_POST_VOTE', dir)
  }
}
