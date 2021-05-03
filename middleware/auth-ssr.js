import { parse as parseCookie } from 'cookie'

export default async function(context) {
  if (process.server && context.req.headers.cookie != null) {
    try {
      const cookies = parseCookie(context.req.headers.cookie)

      const xsrf = cookies['XSRF-TOKEN'] || false
      if (!xsrf) {
        return
      }
      context.$axios.setHeader('Referer', process.env.BROWSER_URL)
      const res = await context.$axios.$get('/user')
      await context.$auth.strategy.token.set('XSRF-TOKEN', xsrf)
      await context.$auth.setUser(res.data)
      context.$auth.$state.loggedIn = true
      //   await context.$auth.setUser(context.$auth.user)
    } catch (e) {
      console.error('debugAuthMiddleware', e)
    }
  }
}
