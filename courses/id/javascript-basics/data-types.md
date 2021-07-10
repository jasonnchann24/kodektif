---
course: Javascript Basics
chapter: Data Types
description: Learn the basics of Javascript, Javascript Data Types.
order: 3
function_name: types
initial_code: |
  function types(value){
    //ubah kode di bawah ini

    //ubah kode di atas ini
  }
test_cases:
  - input:
      - '"Hello World"'
    expect: 'string'
  - input:
      - 10
    expect: 'number'
  - input:
      - true
    expect: 'boolean'
---

# Javascript Dasar

## Bagian: Tipe Data

---

Seperti banyak bahasa pemograman lain, Javascript juga memiliki berbagai tipe data.

Beberapa tipe data dasar dalam Javascript :

- Number -> angka apapun, bilangan bulat, desimal, dan sebagainya. Bilangan bulat memiliki limitasi ( 2^53 - 1 )
- String -> _string_ apapun. cth. "Nama saya John. Saya berumur 20 tahun"
- Boolean -> `true` / `false`

Kita dapat menggunakan `typeof` untuk melihat tipe data pada suatu nilai.

contoh:

```js
let nama = 'Hello'
typeof nama // string

typeof 0 // number

typeof false // boolean
```

---

#### Tugas

Log ke dalam konsol dengan `console.log()`, tipe data yang dikirimkan melalui fungsi argumen.

---

#### Contoh Uji

1. ```js
   assert.equal(types('Hello World'), 'string')
   ```

2. ```js
   assert.equal(types(10), 'number')
   ```
