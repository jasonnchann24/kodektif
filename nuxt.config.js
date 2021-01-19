import i18n from './config/i18n'

export default {
  /*
   ** Headers of the page
   */
  head: {
    title: process.env.npm_package_name || '',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      {
        hid: 'description',
        name: 'description',
        content: process.env.npm_package_description || ''
      }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      {
        rel: 'stylesheet',
        href:
          'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap'
      },
      {
        rel: 'stylesheet',
        type: 'text/css',
        href: 'https://cdn.lineicons.com/2.0/LineIcons.css'
      }
    ]
  },
  /*
   ** Customize the progress-bar color
   */
  loading: { color: '#fff' },
  components: true,
  /*
   ** Global CSS
   */
  css: ['@/assets/scss/main.scss'],
  /*
   ** Plugins to load before mounting the App
   */
  plugins: ['@/plugins/bootstrap.js'],
  /*
   ** Nuxt.js dev-modules
   */
  buildModules: [
    '@nuxtjs/eslint-module',
    [
      'nuxt-i18n',
      {
        strategy: 'prefix_and_default',
        defaultLocale: 'en',
        locales: [
          {
            code: 'en',
            name: 'English'
          },
          {
            code: 'id',
            name: 'Bahasa Indonesia'
          }
        ],
        vueI18n: i18n
      }
    ]
  ],
  /*
   ** Nuxt.js modules
   */
  modules: [
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    '@nuxtjs/dotenv',
    '@nuxtjs/style-resources'
  ],

  styleResources: {
    scss: '@/assets/scss/_variables.scss'
  },
  /*
   ** Axios module configuration
   ** See https://axios.nuxtjs.org/options
   */
  axios: {
    credentials: true,
    baseURL: process.env.BASE_URL || 'http://localhost:8000/api/',
    https: process.env.HTTPS_BOOLEAN || false,
    proxy: false
  },

  auth: {
    localStorage: false,
    redirect: {
      login: '/auth/login',
      // logout: '/',
      // callback: '/login',
      home: '/'
    },
    strategies: {
      local: {
        token: {
          required: false,
          type: false
        },
        user: {
          property: 'data'
        },
        endpoints: {
          user: {
            url: '/user',
            method: 'get'
          },
          logout: {
            method: 'post',
            url: '/logout'
          }
        }
      }
    }
  },
  /*
   ** Build configuration
   */
  build: {
    /*
     ** You can extend webpack config here
     */
    extend(config, { isDev, isClient }) {
      config.module.rules.push({
        test: /\.worker\.js$/,
        use: { loader: 'worker-loader' }
      })

      if (isDev && isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /(node_modules)/
        })
      }
    }
  }
}
