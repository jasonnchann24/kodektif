<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-4">
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
            <nuxtLink :to="localePath('/auth/login')" class="nav-link" tag="a">
              {{ $t('navbar.login') }}
            </nuxtLink>
          </li>
          <li v-else class="nav-item dropdown">
            <a
              id="navbarDropdownMenuLink"
              class="nav-link dropdown-toggle"
              href="#"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              User
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <li><a class="dropdown-item" href="#">Profile</a></li>
              <li><a class="dropdown-item" href="#">Sign Out</a></li>
            </ul>
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
      isAuthenticated: 'isAuthenticated'
    })
  }
}
</script>

<style></style>
