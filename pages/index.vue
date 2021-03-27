<template>
  <div class="p-0">
    <header>
      <div class="container-fluid">
        <div class="row mt-4 mx-0 header-hero w-100">
          <div class="col-12 px-0">
            <div class="hero-container text-start text-md-end">
              <div
                class="hero-img d-flex justify-content-center d-md-block justify-content-md-end"
              >
                <SvgImageDeveloper />
              </div>
              <div class="centered-text text-start">
                <h1>{{ $t('landing.welcome-title') }}</h1>
                <h4>{{ $t('landing.welcome-subtitle') }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>
    <main role="main" class="bg-secondary text-dark py-5 rounded shadow-lg">
      <div class="container">
        <div class="row">
          <div class="col-12 text-center">
            <h1>{{ $t('landing.main.title') }}</h1>
          </div>
        </div>
        <div class="row mt-3 g-4 mx-auto d-flex justify-content-center">
          <template v-for="(item, index) in cardList">
            <div :key="index" class="col-12 col-md-6">
              <div
                class="card mx-auto rounded shadow border-0 hover-card-scale"
              >
                <div class="card-img-top text-center py-2">
                  <i class="ri-4x" :class="item.icon"></i>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col text-center">
                      <h2 class="h4">{{ item.title }}</h2>
                      <p class="mb-0">{{ item.description }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
export default {
  name: 'LandingPage',
  data() {
    return {
      cardList: [
        {
          title: 'Help',
          description: 'some quick example text to build on the card',
          icon: 'ri-hand-heart-line'
        },
        {
          title: 'Free',
          description: 'some quick example text to build on the card',
          icon: 'ri-service-line'
        },
        {
          title: 'Community',
          description: 'some quick example text to build on the card',
          icon: 'ri-group-line'
        },
        {
          title: 'Learn',
          description: 'some quick example text to build on the card',
          icon: 'ri-stack-line'
        }
      ]
    }
  }
}
</script>
<style scoped>
.header-hero {
  margin: 5%;
}

.hero-container {
  position: relative;
  overflow: hidden;
}

.hero-img {
  margin-right: 0%;
}

.centered-text {
  position: absolute;
  top: 35%;
  left: 10%;
}

.hover-card-scale {
  transition: all 0.5s ease;
}
.hover-card-scale:hover {
  transform: scale(1.04);
}
</style>
