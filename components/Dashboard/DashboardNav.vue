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
      <a class="nav-link" href="#">Link</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Link</a>
    </li>
  </ul>
</template>

<script>
export default {
  name: 'DashboardNav'
}
</script>

<style></style>
