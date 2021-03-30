<template>
  <div v-if="show">
    <div class="modal d-block text-dark" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add Image</h5>
          </div>
          <div class="modal-body mb-4">
            <div class="container">
              <div class="row">
                <div class="col">
                  <ul class="nav nav-pills nav-fill mb-4">
                    <li class="nav-item">
                      <a
                        class="nav-link p-3"
                        :class="{ active: tab == 1 }"
                        href="javascript:void(0)"
                        @click="tab = 1"
                        >Link</a
                      >
                    </li>
                    <li class="nav-item">
                      <a
                        class="nav-link p-3"
                        :class="{ active: tab == 2 }"
                        href="javascript:void(0)"
                        @click="tab = 2"
                        >Upload</a
                      >
                    </li>
                  </ul>
                  <div v-if="tab == 1">
                    <label for="url"
                      >Image from internet, paste URL below:</label
                    >
                    <input
                      id="url"
                      v-model="imageSrc"
                      type="text"
                      class="form-control"
                    />
                  </div>
                  <div v-if="tab == 2">
                    <label for="altImage">Alt Image:</label>
                    <input
                      id="altImage"
                      v-model="alt"
                      type="text"
                      class="form-control mb-3"
                    />
                    <label for="up">Upload Image:</label>
                    <input
                      id="up"
                      ref="file"
                      type="file"
                      @change="fileChange"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger text-white" @click="show = false">
              Close modal
            </button>
            <button
              class="btn btn-success text-white"
              :title="validImage ? '' : 'Image URL needs to be valid'"
              :disabled="!validImage"
              @click="insertImage"
            >
              Add Image
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {},
  data() {
    return {
      imageSrc: '',
      command: null,
      show: false,
      tab: 1,
      alt: ''
    }
  },
  computed: {
    validImage() {
      return (
        this.imageSrc.match(/unsplash/) !== null ||
        this.imageSrc.match(/\.(jpeg|jpg|gif|png)$/) != null
      )
    }
  },
  methods: {
    showModal(command) {
      this.command = command
      this.show = true
    },

    fileChange() {
      const file = this.$refs.file.files[0]
      let formData = new FormData()

      formData.append('image', file)

      this.$axios.$post('/base-images', formData).then((res) => {
        this.imageSrc = res
      })
    },
    insertImage() {
      const data = {
        command: this.command,
        data: {
          src: this.imageSrc,
          alt: this.alt
          // title: "YOU CAN ADD TITLE"
        }
      }

      this.$emit('onConfirm', data)
      this.closeModal()
    },

    closeModal() {
      this.show = false
      this.imageSrc = ''
      this.tab = 1
    }
  }
}
</script>

<style scoped>
.nav-link {
  color: rgb(34, 34, 34);
}

.form-control {
  color: rgb(34, 34, 34);
}
</style>
