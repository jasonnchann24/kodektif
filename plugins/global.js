export default (context, inject) => {
  const delay = async (x = 1000) => {
    const delayPromise = (ms) =>
      new Promise((resolve) => setTimeout(resolve, ms))
    await delayPromise(x)
  }

  inject('delay', delay)
}
