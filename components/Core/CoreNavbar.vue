<template>
  <nav
    class="navbar navbar-expand-lg navbar-dark bg-transparent border-bottom border-warning border-1"
  >
    <div class="container px-4">
      <nuxtLink :to="localePath('/')" class="navbar-brand" tag="a">
        <img
          src="/logo/logo_kodektif_bg.svg"
          width="40"
          class="d-inline-block align-text-center"
          alt=""
          srcset=""
        />
      </nuxtLink>
      <button
        id="navbarToggle"
        class="navbar-toggler border-0"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNavDropdown"
        aria-controls="navbarNavDropdown"
        aria-expanded="false"
        aria-label="Toggle navigation"
        @click="collapse = !collapse"
      >
        <i class="ri-menu-5-line ri-lg"></i>
      </button>
      <div
        id="navbarNavDropdown"
        class="collapse navbar-collapse justify-content-between align-items-center mt-3 mt-lg-0"
        style="z-index: 10"
      >
        <ul class="navbar-nav"></ul>
        <ul class="navbar-nav">
          <li class="nav-item">
            <nuxtLink
              :to="localePath('/articles')"
              class="nav-link"
              active-class="active"
              >Articles</nuxtLink
            >
          </li>
          <li class="nav-item">
            <a href="" class="nav-link">Discussions</a>
          </li>
          <li class="nav-item">
            <nuxtLink
              :to="localePath('/courses')"
              class="nav-link"
              active-class="active"
              >Courses</nuxtLink
            >
          </li>
          <li v-if="isAdmin" class="nav-item">
            <nuxtLink
              :to="localePath('/dashboard')"
              class="nav-link"
              active-class="active"
              >Dashboard</nuxtLink
            >
          </li>
        </ul>
        <ul class="navbar-nav ">
          <CoreLangChange />
          <li v-if="!isAuthenticated" class="nav-item">
            <nuxtLink
              :to="localePath('/auth/login')"
              class="nav-link"
              tag="a"
              active-class="active"
            >
              {{ $t('navbar.login') }}
            </nuxtLink>
          </li>
          <li v-else class="nav-item">
            <nuxtLink
              :to="localePath(`/user/${loggedInUser.id}/profile`)"
              class="nav-link"
              tag="a"
              active-class="active"
            >
              <!-- <span class="text-uppercase pe-2">
                {{ $t('navbar.profile') }}
              </span> -->
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
  name: 'CoreNavbar',
  data() {
    return {
      collapse: false
    }
  },
  computed: {
    ...mapGetters({
      isAuthenticated: 'isAuthenticated',
      loggedInUser: 'loggedInUser',
      isAdmin: 'isAdmin'
    })
  },
  watch: {
    collapse() {
      const el = document.getElementById('navbarToggle').classList
      this.collapse ? el.add('rotate') : el.remove('rotate')
    },
    '$route.path'() {
      if (screen.width < 745) {
        if (this.collapse) {
          document.getElementById('navbarToggle').click()
        }
      }
    }
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

.navbar-dark .navbar-nav .nav-link:hover {
  color: rgba(255, 255, 255, 0.95);
}

.navbar-toggler:focus {
  outline: none;
  box-shadow: none;
}

.rotate {
  transform: rotate(-90deg);
  transition: all 0.7s ease;
}

.navbar-toggler {
  transition: all 0.5s ease;
}

.dropdown .dropdown-menu {
  height: 0px;
  display: block;
  overflow: hidden;
  opacity: 0;
  -webkit-transition: all 3s ease;
  -moz-transition: all 3s ease;
  -ms-transition: all 3s ease;
  -o-transition: all 3s ease;
  transition: all 3s ease;
}

.dropdown.open .dropdown-menu {
  height: 200px;
  opacity: 1;
}
</style>
