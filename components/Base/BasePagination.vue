<template>
  <nav v-if="getData.meta" aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
      <li class="page-item">
        <a
          name="navButtons"
          class="page-link"
          :class="{ 'disabled pe-none text-muted': dataLinks.prev == null }"
          href="javascript:void(0)"
          @click="changePage('prev')"
          >Previous</a
        >
      </li>
      <li class="page-item mx-2 d-flex align-items-center">
        <v-select
          v-model="currentPage"
          class="style-chooser "
          :options="pageIteration"
          append-to-body
        ></v-select>
      </li>
      <li class="page-item">
        <a
          name="navButtons"
          class="page-link"
          :class="{ 'disabled pe-none text-muted': dataLinks.next == null }"
          href="javascript:void(0)"
          @click="changePage('next')"
          >Next</a
        >
      </li>
    </ul>
  </nav>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  name: 'BasePagination',
  props: {
    module: {
      type: String,
      default: ''
    },
    getter: {
      type: String,
      default: ''
    },
    action: {
      type: String,
      default: ''
    },
    totalPages: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      pageIteration: [],
      currentPage: 1
    }
  },
  computed: {
    ...mapGetters({
      LOADING: 'isLoading'
    }),
    getData() {
      return this.$store.getters[`${this.module}/${this.getter}`] ?? ''
    },
    dataLinks() {
      return this.getData.links
    }
  },
  watch: {
    currentPage: function() {
      if (!this.currentPage) {
        this.currentPage = 1
      }
      this.updateData()
    }
  },
  mounted() {
    this.initPageIteration()
  },
  methods: {
    ...mapActions({
      UPDATE_LOADING: 'UPDATE_LOADING'
    }),
    initPageIteration() {
      for (let i = 1; i <= this.totalPages; i++) {
        this.pageIteration.push(i)
      }
    },
    async updateData() {
      this.UPDATE_LOADING(true)
      try {
        await this.$store.dispatch(`${this.module}/${this.action}`, {
          page: this.currentPage
        })
      } catch (err) {
        this.$toast.error(
          `Sorry.. Something went wrong! ${err.response.statusText}`
        )
      } finally {
        this.UPDATE_LOADING(false)
      }
    },
    changePage(direction) {
      document.getElementsByName('navButtons').forEach((x) => x.blur())
      if (direction === 'next') {
        if (this.dataLinks.next !== null) {
          this.currentPage += 1
          return
        }
        return
      }

      if (direction === 'prev') {
        if (this.dataLinks.prev !== null) {
          this.currentPage -= 1
          return
        }
        return
      }
    }
  }
}
</script>

<style>
.style-chooser .vs__search::placeholder,
.style-chooser .vs__dropdown-toggle,
.style-chooser .vs__dropdown-menu {
  background: white;
  border: none;
  color: #474b60;
  text-transform: lowercase;
  font-variant: small-caps;
  width: 7rem;
  padding-bottom: 2px;
}

.style-chooser .vs__clear,
.style-chooser .vs__open-indicator {
  fill: #474b60;
}
</style>
