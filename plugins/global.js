export default (context, inject) => {
  const delay = async (x = 1000) => {
    const delayPromise = (ms) =>
      new Promise((resolve) => setTimeout(resolve, ms))
    await delayPromise(x)
  }

  const slugify = (x) => {
    if (!x) {
      return
    }
    return x
      .toString()
      .toLowerCase()
      .replace(/\s+/g, '-') // Replace spaces with -
      .replace(/[^\w-]+/g, '') // Remove all non-word chars
      .replace(/--+/g, '-') // Replace multiple - with single -
      .replace(/^-+/, '') // Trim - from start of text
      .replace(/-+$/, '')
  }

  inject('delay', delay)
  inject('slugify', slugify)
}
