<i18n>
{
  "en": {
    "previous": "Previous",
    "next": "Next",
    "no-match": "Sorry, no matching options"
  },
  "id": {
    "previous": "Sebelumnya",
    "next": "Selanjutnya",
    "no-match": "Maaf, tidak ada pilihan"
  }
}
</i18n>

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
          >{{ $t('previous') }}</a
        >
      </li>
      <li class="page-item mx-2 d-flex align-items-center">
        <v-select
          v-model="currentPage"
          class="style-chooser "
          :options="pageIteration"
          :clearable="false"
          append-to-body
          ><span slot="no-options" class="text-danger">
            {{ $t('no-match') }}
          </span></v-select
        >
      </li>
      <li class="page-item">
        <a
          name="navButtons"
          class="page-link"
          :class="{ 'disabled pe-none text-muted': dataLinks.next == null }"
          href="javascript:void(0)"
          @click="changePage('next')"
          >{{ $t('next') }}</a
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
    },
    topPage: {
      type: String,
      default: 'top-main'
    },
    additionalParams: {
      type: Object,
      default: () => {}
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
        const page = {
          page: this.currentPage
        }
        const additional = this.additionalParams

        const payload = { ...page, ...additional }
        await this.$store.dispatch(`${this.module}/${this.action}`, payload)
        this.$scrollTo(`#${this.topPage}`)
      } catch (err) {
        this.$toast.error(
          `Sorry.. Something went wrong! ${'Sorry! Something went wrong. Please try again later.'}`
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
  width: 5rem;
  height: 3rem;
}

.style-chooser .vs__clear,
.style-chooser .vs__open-indicator {
  fill: #474b60;
}

.vs__selected {
  padding: 7px;
  border-radius: 5px;
  background: #a0a0a05e;
}

.vs__deselect {
  fill: #fff;
}
</style>
