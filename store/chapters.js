export const state = () => ({
  chapter: {}
})

export const getters = {
  CHAPTER(state) {
    return state.chapter
  }
}

export const mutations = {
  SET_CHAPTER(state, payload) {
    state.chapter = payload
  }
}

export const actions = {
  async CREATE_CHAPTER({ commit }, payload) {
    const res = await this.$axios.$post('chapters', payload)
    commit('SET_CHAPTER', res.data)
  },
  async UPDATE_CHAPTER({ commit }, { id, payload }) {
    const res = await this.$axios.$patch(`chapters/${id}`, payload)
    commit('SET_CHAPTER', res.data)
  },
  async DELETE_CHAPTER({ commit }, id) {
    await this.$axios.$delete(`chapters/${id}`)
    commit('SET_CHAPTER', {})
  }
}
