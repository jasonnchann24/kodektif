<template>
  <div class="d-flex flex-column text-center">
    <i
      class="ri-arrow-up-s-line ri-2x  click-vote"
      :class="{
        'text-success': isUpVote
      }"
      @click="handleClick('up')"
    ></i>
    <p class="m-0">{{ voteScore }}</p>
    <i
      class="ri-arrow-down-s-line ri-2x click-vote"
      :class="{
        'text-danger': isDownVote
      }"
      @click="handleClick('down')"
    ></i>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  name: 'BaseVote',
  props: {
    module: {
      type: String,
      default: ''
    },
    model: {
      type: Object,
      default: () => {}
    }
  },
  computed: {
    voteScore() {
      if (this.model.upvote_count < 1 && this.model.downvote_count < 1) {
        return 0
      }

      return parseInt(this.model.upvote_count - this.model.downvote_count)
    },
    isUpVote() {
      return this.model.has_voted?.upvote
    },
    isDownVote() {
      return this.model.has_voted?.upvote === false
    }
  },
  methods: {
    ...mapActions({
      UPDATE_LOADING: 'UPDATE_LOADING'
    }),
    handleClick(direction) {
      const upvote = direction == 'up'
      if (!this.model.has_voted) {
        return this.createVote(direction)
      }

      if (this.model.has_voted.upvote != upvote) {
        return this.updateVote(direction)
      }

      return this.deleteVote()
    },
    async createVote(direction) {
      const payload = {
        upvote: direction == 'up'
      }
      payload[`${this.getModelIdSnakeCase()}_id`] = this.model.id
      const actionName = 'CREATE_' + this.getActionVuexName() + '_VOTE'
      try {
        this.UPDATE_LOADING()

        await this.$store.dispatch(`${this.module}/${actionName}`, payload)
      } catch (err) {
        this.$toast.error(err.response.statusText)
      } finally {
        this.UPDATE_LOADING(false)
      }
    },
    async updateVote(direction) {
      const payload = {
        upvote: direction == 'up'
      }
      payload[`${this.getModelIdSnakeCase()}_vote_id`] = this.model.has_voted.id
      const actionName = 'UPDATE_' + this.getActionVuexName() + '_VOTE'
      try {
        this.UPDATE_LOADING()

        await this.$store.dispatch(`${this.module}/${actionName}`, payload)
      } catch (err) {
        this.$toast.error(err.response.statusText)
      } finally {
        this.UPDATE_LOADING(false)
      }
    },
    async deleteVote(direction) {
      const payload = {
        upvote: direction == 'up',
        id: this.model.has_voted.id
      }
      const actionName = 'DELETE_' + this.getActionVuexName() + '_VOTE'
      try {
        this.UPDATE_LOADING()

        await this.$store.dispatch(`${this.module}/${actionName}`, payload)
      } catch (err) {
        // this.$toast.error(err.response.statusText)
        console.log(err)
      } finally {
        this.UPDATE_LOADING(false)
      }
    },
    getActionVuexName() {
      const name = this.module.slice(0, -1)

      const re = /(?=[A-Z])/
      let wordSplit = name.split(re)

      return wordSplit.map((x) => x.toUpperCase()).join('_')
    },
    getModelIdSnakeCase() {
      const name = this.module.slice(0, -1)
      const re = /(?=[A-Z])/
      let wordSplit = name.split(re)
      return wordSplit.map((x) => x.toLowerCase()).join('_')
    }
  }
}
</script>

<style>
.click-vote {
  cursor: pointer;
}
</style>
