<template>
  <li class="nav-item dropdown">
    <a
      id="dropDownLang"
      class="nav-link dropdown-toggle"
      href="#"
      role="button"
      data-bs-toggle="dropdown"
      aria-expanded="false"
    >
      <img class="mr-1" :src="getFlag($i18n.locale)" alt="" />
    </a>
    <ul class="dropdown-menu overflow-hidden" aria-labelledby="dropDownLang">
      <li v-for="locale in $i18n.locales" :key="locale.code">
        <nuxt-link
          class="dropdown-item"
          :to="switchLocalePath(locale.code)"
          tag="a"
          >{{ locale.name }} -
          <img class="ml-2 mr-4" :src="getFlag(locale.code)" alt="" />
        </nuxt-link>
      </li>
    </ul>
  </li>
</template>

<script>
export default {
  name: 'LangSelect',
  computed: {
    currLocale() {
      return this.$i18n.locale
    }
  },
  methods: {
    getFlag(code) {
      const dir = '/icons/flags'
      return `${dir}/${code}.svg`
    }
  }
}
</script>
