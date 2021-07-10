---
course: Javascript Basics
chapter: Variables
description: Learn the basics of javascript, Javascript Variables.
order: 2
function_name: variable
initial_code: |
  function variable(value){
    //ubah kode di bawah ini

    //ubah kode di atas ini
  }
test_cases:
  - input:
      - '"Hello World"'
    expect: 'Hello World'
  - input:
      - 10
    expect: 10
---

# Javascript Dasar

## Bagian: _Variables_

---

Anda bisa menentukan variabel dalam Javascript dengan `let` / `const`

contoh:

```js
let nama = 'Hello'
const harga = 10
```

Perbedaan antara `let` & `const` adalah `let` dapat ditentukan ulang nilainya sedangkan, `const` tidak dapat ditentukan ulang nilainya.

```js
let nilai = 1
nilai = 2
console.log(nilai) // returns 2

const korting = 0.5
korting = 1 // akan error
```

---

#### Tugas

_Log_ ke dalam konsol dari variabel yang Anda telah buat. Variabel harus menyimpan nilai dari fungsi yang dikirimkan dalam argumen.

---

#### Contoh Uji

1. ```js
   assert.equal(variable('Hello World'), 'Hello World')
   ```

2. ```js
   assert.equal(variable(10), 10)
   ```
