<i18n>
{
  "en": {
     "meta": {
        "title": "Welcome - Kodektif",
        "description": "Learn Programming with Freedom!"
      }
  },
  "id": {
     "meta": {
        "title": "Selamat Datang - Kodektif",
        "description": "Belajar Pemograman dengan Bebas!"
      }
  }
}
</i18n>

<template>
  <div class="p-0">
    <SocialHead
      :title="$t('meta.title')"
      :description="$t('meta.description')"
    />
    <header>
      <div class="container-fluid">
        <div class="row mt-4 mx-0 header-hero w-100">
          <div class="col-12 px-0">
            <div class="hero-container text-start text-md-end">
              <div
                class="hero-img d-flex justify-content-center d-md-block justify-content-md-end"
                data-aos="fade-left"
                data-aos-duration="900"
              >
                <SvgImageDeveloper :width="800" />
              </div>
              <div
                class="centered-text text-start"
                data-aos="fade-right"
                data-aos-duration="800"
                data-aos-delay="300"
              >
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
          <div
            class="col-12 text-center"
            data-aos="fade-down"
            data-aos-duration="500"
          >
            <h1>{{ $t('landing.main.title') }}</h1>
          </div>
        </div>
        <div class="row mt-3 g-4 mx-auto d-flex justify-content-center">
          <template v-for="(item, index) in cardList">
            <div :key="index" class="col-12 col-md-6">
              <div
                class="card mx-auto rounded shadow border-0 "
                data-aos="fade-down"
                :data-aos-duration="index * 100 + 400"
              >
                <div class="card-img-top text-center py-2">
                  <i class="ri-4x" :class="item.icon"></i>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col text-center ">
                      <h2 class="h4">
                        {{ $t(`landing.${item.title}.title`) }}
                      </h2>
                      <p class="mb-0">
                        {{ $t(`landing.${item.description}.description`) }}
                      </p>
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
          title: 'why-help',
          description: 'why-help',
          icon: 'ri-hand-heart-line'
        },
        {
          title: 'why-free',
          description: 'why-free',
          icon: 'ri-service-line'
        },
        {
          title: 'why-community',
          description: 'why-community',
          icon: 'ri-group-line'
        },
        {
          title: 'why-learn',
          description: 'why-learn',
          icon: 'ri-stack-line'
        }
      ]
    }
  },
  head() {
    return {
      title: this.$t('meta.title'),
      description: this.$t('meta.description')
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

@media screen and (max-width: 692px) {
  .hero-img {
    margin-right: 0;
    margin-top: 100px;
  }
}

.hero-img {
  margin-right: 5%;
}

.centered-text {
  position: absolute;
  top: 35%;
  left: 10%;
}

@media screen and (max-width: 692px) {
  .centered-text {
    position: absolute;
    top: 0%;
    left: 10%;
  }
}

.hover-card-scale {
  transition: all 0.3s ease;
}
.hover-card-scale:hover {
  transform: scale(1.04);
}
</style>
