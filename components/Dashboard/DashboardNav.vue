<template>
  <ul class="nav nav-pills nav-fill text-white">
    <li class="nav-item">
      <nuxt-link
        to="/dashboard"
        class="nav-link"
        aria-current="page"
        exact-active-class="active"
        >Dashboard Home</nuxt-link
      >
    </li>
    <li class="nav-item">
      <nuxt-link
        to="/dashboard/articles-management"
        class="nav-link"
        aria-current="page"
        exact-active-class="active"
        >Articles Management</nuxt-link
      >
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">User Management</a>
    </li>
    <nuxt-link
      to="/dashboard/courses-management"
      class="nav-link"
      aria-current="page"
      exact-active-class="active"
      >Courses Management</nuxt-link
    >
  </ul>
</template>

<script>
export default {
  name: 'DashboardNav'
}
</script>

<style></style>
