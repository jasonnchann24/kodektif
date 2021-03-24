<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
    <div class="container px-4">
      <nuxtLink :to="localePath('/')" class="navbar-brand" tag="a">
        <img
          src="/logo/logo_kodektif_bg.svg"
          width="50"
          class="d-inline-block align-text-top"
          alt=""
          srcset=""
        />
      </nuxtLink>
      <button
        class="navbar-toggler border-0"
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
          <li class="nav-item">
            <a href="" class="nav-link">Articles</a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link">Discussions</a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link">Courses</a>
          </li>
        </ul>
        <ul class="navbar-nav">
          <li v-if="!isAuthenticated" class="nav-item">
            <nuxtLink :to="localePath('/auth/login')" class="nav-link" tag="a">
              {{ $t('navbar.login') }}
            </nuxtLink>
          </li>
          <li v-else class="nav-item">
            <nuxtLink
              :to="localePath('/user/profile')"
              class="nav-link"
              tag="a"
            >
              <span class="text-uppercase pe-2">
                {{ $t('navbar.profile') }}
              </span>
              <img
                :src="loggedInUser.provider.avatar"
                alt=""
                width="35"
                height="35"
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

<style scoped>
.navbar {
  height: 4rem;
}

@media screen and (max-width: 992px) {
  .navbar-collapse {
    background-color: #474b60;
    padding: 0 20px 0 20px;
    border-radius: 5px;
  }
}
</style>
