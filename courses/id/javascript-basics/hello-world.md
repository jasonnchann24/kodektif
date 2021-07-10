---
course: Javascript Basics
chapter: Hello World
description: Learn to log hello world!
order: 1
function_name: func
initial_code: |
  function func(value){
    //ubah kode di bawah ini

    //ubah kode di atas ini
  }
test_cases:
  - input:
      - '"Hello World"'
    expect: 'Hello World'
---

# Javascript Dasar

## Bagian: Hello World

---

Pada kursus ini, Anda akan mempelajari bagaimana melakukan _print_ ke dalam konsol dengan Javascript.

Pada bahasa pemograman Javascript, Anda dapat menggunakan fungsi `console.log()` untuk menampilkan _output_ yang Anda inginkan ke dalam konsol. Ini merupakan fungsi bawaan di dalam Javascript.

contoh:

```js
console.log('Hello World!')
// logs Hello World! ke dalam konsol.
```

```js
const a = 'Kodektif'
console.log(a) // Logs Kodektif
```

---

#### Tugas

Log ke dalam konsol nilai apapun yang di kirimkan kedalam fungsi bernama `func`.

---

#### Contoh Uji

1. ```js
   assert.equal(func('Hello World'), 'Hello World')
   ```
