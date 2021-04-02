<template>
  <div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-lg-6">
          <nuxt-content :document="doc" />
        </div>
        <div class="col-12 col-lg-6">
          <client-only>
            <CoreCodeEditor />
          </client-only>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CoursesIndex',
  async asyncData({ $content, app }) {
    const doc = await $content(
      `${app.i18n.localeProperties.code}/javascript-basics`,
      {
        deep: true
      }
    ).fetch()
    return { doc }
  }
}
</script>

<style></style>
