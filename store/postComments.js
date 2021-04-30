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
  },
  CREATED_POST_COMMENT_VOTE(state, payload) {
    const comment = state.post_comments.data.find(
      (x) => x.id == payload.data.post_comment_id
    )
    comment.has_voted = payload.data
    payload.data.upvote
      ? (comment.upvote_count += 1)
      : (comment.downvote_count += 1)
  },
  UPDATED_POST_COMMENT_VOTE(state, payload) {
    const comment = state.post_comments.data.find(
      (x) => x.id == payload.data.post_comment_id
    )
    comment.has_voted = payload.data

    if (payload.data.upvote) {
      comment.downvote_count -= 1
      comment.upvote_count += 1
    } else {
      comment.downvote_count += 1
      comment.upvote_count -= 1
    }
  },
  DELETED_POST_COMMENT_VOTE(state, payload) {
    const comment = state.post_comments.data.find(
      (x) => x.has_voted.id == payload.id
    )
    comment.has_voted = null
    console.log(comment)
    payload.upvote ? (comment.upvote_count -= 1) : (comment.downvote_count -= 1)
  },
  CREATED_POST_COMMENT_REPLY(state, payload) {
    const comment = state.post_comments.data.find(
      (x) => x.id == payload.data.post_comment_id
    )
    comment.replies.unshift(payload.data)
  },
  DELETED_POST_COMMENT_REPLY(state, payload) {
    const comment = state.post_comments.data.find(
      (x) => x.id == payload.post_comment_id
    )
    const idx = comment.replies.findIndex((reply) => reply.id == payload.id)

    comment.replies.splice(idx, 1)
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
  },

  async CREATE_POST_COMMENT_VOTE({ commit }, payload) {
    const res = await this.$axios.$post('post-comment-votes', payload)
    commit('CREATED_POST_COMMENT_VOTE', res)
  },
  async UPDATE_POST_COMMENT_VOTE({ commit }, payload) {
    const res = await this.$axios.$patch(
      `post-comment-votes/${payload.post_comment_vote_id}`,
      {
        upvote: payload.upvote
      }
    )
    commit('UPDATED_POST_COMMENT_VOTE', res)
  },
  async DELETE_POST_COMMENT_VOTE({ commit }, payload) {
    await this.$axios.$delete(`post-comment-votes/${payload.id}`)
    commit('DELETED_POST_COMMENT_VOTE', payload)
  },
  async CREATE_POST_COMMENT_REPLY({ commit }, payload) {
    const res = await this.$axios.$post(`post-comment-replies`, payload)
    commit('CREATED_POST_COMMENT_REPLY', res)
  },
  async DELETE_POST_COMMENT_REPLY({ commit }, payload) {
    await this.$axios.$delete(`post-comment-replies/${payload.id}`)
    commit('DELETED_POST_COMMENT_REPLY', payload)
  }
}
