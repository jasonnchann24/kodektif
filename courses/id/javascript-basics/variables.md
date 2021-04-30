---
course: Javascript Basics
chapter: Variables
description: Learn the basics of javascript, Javascript Variables.
order: 1
function_name: variable
initial_code: |
  function variable(value){
    // ketik kode dibawah ini

    // ketik kode diatas ini
  }
test_cases:
  - input:
      - '"Halo Dunia"'
    expect: 'Halo Dunia'
  - input:
      - 10
    expect: 10
---

# Pengenalan Javascript

## Bagian: Variabel

---

Anda dapat mendefinisikan variabel pada javascript dengan `let` / `const`

contoh:

```js
let nama = 'Kodektif'
const harga = 10
```

Perbedaan antara `let` & `const` adalah jika `let` dapat di tetapkan ulang nilainya, sedangkan `const` tidak.

```js
let value = 1
value = 2
console.log(value) // returns 2 / hasilnya 2

const korting = 0.5
korting = 1 // error
```

---

#### Tugas

Munculkan hasil variabel yang telah Anda buat ke dalam _console output_. Variabel harus menyimpan nilai dari argument yang terdapat pada fungsi.

---

#### Contoh Pengujian

1. ```js
   assert.equal(variable('Halo Dunia'), 'Halo Dunia')
   ```

2. ```js
   assert.equal(variable(10), 10)
   ```
