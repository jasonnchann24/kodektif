export const state = () => ({
  categories: {
    data: []
  },
  category: null
})

export const getters = {
  CATEGORIES(state) {
    return state.categories
  },
  CATEGORY(state) {
    return state.category
  }
}

export const mutations = {
  SET_CATEGORIES(state, payload) {
    state.categories = payload
  },
  NEW_CATEGORY(state, payload) {
    if (payload.parent_id) {
      const parent = state.category
      parent.children.push(payload)
      return
    }

    state.categories.data.push(payload)
  },
  SET_CATEGORY(state, payload) {
    state.category = payload
  },
  UPDATE_CATEGORY(state, payload) {
    if (payload.parent_id) {
      const idx = state.category.children.findIndex((x) => {
        return x.id == payload.id
      })
      state.category.children.splice(idx, 1, payload)
      return
    }

    state.category.name = payload.name
  },
  DELETE_CATEGORY(state, payload) {
    if (payload.children) {
      const idx = state.category.children.findIndex((x) => {
        return x.id == payload.id
      })
      state.category.children.splice(idx, 1)
      return
    }

    const idx = state.categories.data.findIndex((x) => {
      return x.id == payload.id
    })

    state.categories.data.splice(idx, 1)
  }
}

export const actions = {
  async GET_CATEGORIES({ commit }) {
    const res = await this.$axios.$get(`categories`)
    commit('SET_CATEGORIES', res)
  },
  async CREATE_CATEGORY({ commit }, payload) {
    const res = await this.$axios.$post('categories', payload)
    commit('NEW_CATEGORY', res.data)
  },
  async UPDATE_CATEGORY({ commit }, { payload, categoryId }) {
    const res = await this.$axios.$patch('categories/' + categoryId, payload)
    commit('UPDATE_CATEGORY', res.data)
  },
  async DELETE_CATEGORY({ commit }, { id, children = false }) {
    await this.$axios.$delete('categories/' + id)
    commit('DELETE_CATEGORY', { id: id, children: children })
  },
  async GET_CATEGORY({ commit, state }, { categoryId }) {
    if (!state.categories.data.length) {
      const res = await this.$axios.$get(`categories/${categoryId}`)
      commit('SET_CATEGORY', res.data)
      return
    }

    const find = new Promise((resolve) => {
      const found = state.categories.data.find((x) => {
        return x.id == categoryId
      })
      resolve(found)
    })

    const category = await find
    commit('SET_CATEGORY', category)
  }
}
