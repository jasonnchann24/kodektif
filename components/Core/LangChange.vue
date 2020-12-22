<template>
  <li class="nav-item dropdown">
    <a
      id="navbarDropdown-3"
      class="nav-link dropdown-toggle"
      href="#"
      role="button"
      data-toggle="dropdown"
      aria-haspopup="true"
      aria-expanded="false"
    >
      <img class="mr-1" :src="getFlag($i18n.locale)" alt="" />
    </a>
    <div
      class="dropdown-menu animate slideIn"
      aria-labelledby="navbarDropdown-3"
    >
      <div @click="navbarToggle">
        <nuxt-link
          v-for="locale in $i18n.locales"
          :key="locale.code"
          class="dropdown-item"
          :to="switchLocalePath(locale.code)"
          >{{ locale.name }} -
          <img class="ml-2 mr-4" :src="getFlag(locale.code)" alt="" />
        </nuxt-link>
      </div>
    </div>
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
    },
    navbarToggle() {
      const element = document.getElementById('dorneNav')
      const elToggle = document.getElementById('toggle-icn')

      if (
        element.classList.contains('show') &&
        window.getComputedStyle(elToggle, ':before')
      ) {
        document.getElementById('navbar-toggle').click()
      }
    }
  }
}
</script>
