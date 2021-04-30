<template>
  <div>
    <div v-for="reply in replies" :key="reply.id" class="row mt-3">
      <div class="col">
        <div class="row">
          <div class="col d-flex justify-content-between">
            <p class="mb-2">by {{ reply.user.name }}</p>
            <i
              v-if="loggedInUser.id == reply.user.id"
              class="ri-delete-bin-line cursor-pointer"
              @click="deleteReply(reply)"
            ></i>
          </div>
        </div>
        <div class="row">
          <div class="col max-reply-height">
            <client-only>
              <CoreHtmlViewer :body-content="reply.body" />
            </client-only>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'CommentReplies',
  props: {
    replies: {
      type: Array,
      default: () => []
    },
    vuexModule: {
      type: String,
      default: ''
    },
    vuexDeleteAction: {
      type: String,
      default: ''
    },
    parentForeignKey: {
      type: String,
      default: ''
    }
  },
  computed: {
    ...mapGetters({
      loggedInUser: 'loggedInUser'
    })
  },
  methods: {
    async deleteReply(reply) {
      try {
        this.$store.dispatch('UPDATE_LOADING')
        const payload = {
          id: reply.id
        }
        payload[`${this.parentForeignKey}`] = reply[`${this.parentForeignKey}`]
        await this.$store.dispatch(
          `${this.vuexModule}/${this.vuexDeleteAction}`,
          payload
        )
        this.$toast.success('Reply deleted')
      } catch (err) {
        console.log(err)
      } finally {
        this.$store.dispatch('UPDATE_LOADING', false)
      }
    }
  }
}
</script>

<style>
.cursor-pointer {
  cursor: pointer;
}

.max-reply-height {
  max-height: 300px;
  overflow: auto;
}
</style>
