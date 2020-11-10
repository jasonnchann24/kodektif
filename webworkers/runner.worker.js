self.addEventListener('message', function(e) {
  self.postMessage({
    code: e.data,
    result: JSON.stringify(new Function(e.data)())
  })
})
