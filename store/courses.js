export const state = () => ({
  courses: [],
  course: {}
})

export const getters = {
  COURSES(state) {
    return state.courses
  },
  COURSE(state) {
    return state.course
  }
}

export const mutations = {
  SET_COURSES(state, payload) {
    state.courses = payload
  },
  SET_COURSE(state, payload) {
    state.course = payload
  },
  DELETE_COURSE(state, id) {
    const index = state.courses.data.findIndex((obj) => {
      return obj.id == id
    })

    if (index !== -1) {
      state.courses.data.splice(index, 1)
    }
  },
  REMOVE_CHAPTER_FROM_COURSE(state, id) {
    const index = state.course.chapters.findIndex((obj) => {
      return obj.id == id
    })

    if (index !== -1) {
      state.course.chapters.splice(index, 1)
    }
  }
}

export const actions = {
  async GET_COURSES({ commit }) {
    const res = await this.$axios.$get('courses')
    commit('SET_COURSES', res)
  },
  async GET_COURSE({ commit, state }, id) {
    let res = null
    if (state.courses.data) {
      res = state.courses.data.find((obj) => {
        return obj.id == id
      })
    }

    if (!res || !state.courses.data) {
      res = await this.$axios.$get(`courses/${id}`)
      res = res.data
    }

    commit('SET_COURSE', res)
  },
  async CREATE_COURSE({ commit }, payload) {
    const res = await this.$axios.$post('courses', payload)
    commit('SET_COURSE', res.data)
  },
  async UPDATE_COURSE({ commit }, { id, payload }) {
    const res = await this.$axios.$patch(`courses/${id}`, payload)
    commit('SET_COURSE', res.data)
  },
  async DELETE_COURSE({ commit }, id) {
    await this.$axios.$delete(`courses/${id}`)
    commit('DELETE_COURSE', id)
  }
}
