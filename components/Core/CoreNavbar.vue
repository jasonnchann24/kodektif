<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-secondary px-2 shadow">
    <div class="container-fluid">
      <nuxtLink :to="localePath('/')" class="navbar-brand" tag="a">
        Kodektif
      </nuxtLink>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNavDropdown"
        aria-controls="navbarNavDropdown"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div
        id="navbarNavDropdown"
        class="collapse navbar-collapse justify-content-between"
      >
        <ul class="navbar-nav">
          <CoreLangChange />
        </ul>
        <ul class="navbar-nav">
          <li v-if="!isAuthenticated" class="nav-item">
            <nuxtLink
              :to="localePath('/auth/login')"
              class="nav-link text-dark"
              tag="a"
            >
              {{ $t('navbar.login') }}
            </nuxtLink>
          </li>
          <li v-else class="nav-item">
            <nuxtLink
              :to="localePath('/user/profile')"
              class="nav-link text-dark"
              tag="a"
            >
              <span class="text-uppercase px-2">
                {{ $t('navbar.profile') }}
              </span>
              <img
                :src="loggedInUser.provider.avatar"
                alt=""
                width="50"
                height="50"
                class="rounded"
              />
            </nuxtLink>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  computed: {
    ...mapGetters({
      isAuthenticated: 'isAuthenticated',
      loggedInUser: 'loggedInUser'
    })
  }
}
</script>

<style></style>
