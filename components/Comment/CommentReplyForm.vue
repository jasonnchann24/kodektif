<template>
  <div>
    <div class="row mt-3">
      <div class="col">
        <client-only>
          <CoreEditor
            id="bodyContent"
            :init-content="initContent"
            @update="updateBody"
          />
        </client-only>
        <button class="btn btn-success text-white mt-3" @click="submitComment">
          Submit
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'CommentReplyForm',
  props: {
    initContent: {
      type: String,
      default: ''
    },
    mainPayload: {
      type: Object,
      default: () => {}
    },
    vuexModule: {
      type: String,
      default: ''
    },
    vuexAction: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      body: ''
    }
  },
  computed: {
    ...mapGetters({
      isAuthenticated: 'isAuthenticated'
    })
  },
  methods: {
    updateBody(value) {
      this.body = value
    },
    async submitComment() {
      try {
        this.$store.dispatch('UPDATE_LOADING')
        let payload = this.mainPayload
        payload['body'] = this.body

        await this.$store.dispatch(
          `${this.vuexModule}/${this.vuexAction}`,
          payload
        )
        this.$emit('submitted')
        this.$toast.success('Submitted')
      } catch (err) {
        console.log(err)
      } finally {
        this.$store.dispatch('UPDATE_LOADING', false)
      }
    }
  }
}
</script>

<style></style>
