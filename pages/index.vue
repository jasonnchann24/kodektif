<template>
  <div class="p-0">
    <header>
      <div class="row mt-4 mx-0 header-hero w-100">
        <div
          class="col-12 my-auto d-flex flex-column justify-content-center px-0"
        >
          <h1>{{ $t('landing.welcome-title') }}</h1>
          <h4>{{ $t('landing.welcome-subtitle') }}</h4>
        </div>
      </div>
    </header>
    <hr />
    <main role="main" class="bg-secondary text-dark py-4 rounded shadow-lg">
      <div class="row mt-4">
        <div class="col-12 text-center">
          <h1>{{ $t('landing.main.title') }}</h1>
        </div>
      </div>
      <div class="row mt-4">
        <template v-for="(i, index) in 3">
          <div :key="index" class="col">
            <div class="card mx-auto rounded shadow" style="width: 18rem;">
              <!-- <img src="..." class="card-img-top" alt="..." /> -->
              <div class="card-body">
                <p class="card-text">
                  Some quick example text to build on the card title and make up
                  the bulk of the card's content.
                </p>
              </div>
            </div>
          </div>
        </template>
      </div>
    </main>
  </div>
</template>

<script>
export default {
  name: 'LandingPage'
}
</script>
<style>
.header-hero {
  background: url('~assets/svgs/developer.svg') no-repeat;
  background-size: 700px 700px;
  background-position: right center;
  object-fit: cover;
  height: 60vh;
}

@media screen and (max-width: 768px) {
  .header-hero {
    background-position: center center;
  }
}
</style>
