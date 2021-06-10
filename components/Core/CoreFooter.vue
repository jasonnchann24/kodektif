<template>
  <footer class="footer mt-auto py-5">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-2">
          <img
            src="/logo/logo_kodektif.svg"
            class="mb-2"
            alt="logo kodektif"
            width="75"
          />
          <small class="d-block mb-3 text-muted"
            >&copy; kodektif.com - {{ new Date().getFullYear() }}</small
          >
        </div>
        <div class="col-6 col-md-10">
          <h5>{{ $t('footer.links') }}</h5>
          <ul class="list-unstyled text-small">
            <li><a class="link-secondary" href="#">Github</a></li>
            <li>
              <a class="link-secondary" href="#">{{
                $t('footer.contribute')
              }}</a>
            </li>
            <li>
              <a class="link-secondary" href="#">{{ $t('footer.license') }}</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'CoreFooter'
}
</script>

<style></style>
