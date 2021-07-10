---
course: Javascript Basics
chapter: Maths & Operators
description: Learn the basics of Javascript, Maths & Operators in Javascript.
order: 4
function_name: myFunction
initial_code: |
  function myFunction(a,b,c){
    //ubah kode di bawah ini

    //ubah kode di atas ini
  }
test_cases:
  - input:
      - 1
      - 2
      - 3
    expect: 6
  - input:
      - 5
      - 5
      - 5
    expect: 15
  - input:
      - -1
      - 10
      - 10
    expect: 19
---

# Javascript Dasar

## Bagian: Maths & Operators

---

Operator dalam pemograman sama dengan matematika dasar, pertambahan, pengurangan, perkalian, dan sebagainya.

Javascript memiliki operator - operator sebagai berikut:

- Pertambahan `+`
- Pengurangan `-`
- Perkalian `*`
- Pembagian `/`
- Sisa `%`
- Pangkat `**`

Operator - operator ini sama dengan matematika dalam sekolah, hanya sisa dan pangkat yang berbeda penulisan.

Contoh penggunaan sisa dan pangkat:

```js
const a = 10
const b = 3

console.log(a % b) // logs 1 (10 / 3 = 3 sisa 1)
console.log(a ** b) // logs 1000 (10³ = 1000)
```

Dalam bahasa pemograman seperti Javascript, kita dapat menggunakan `+` untuk menggabungkan _string_.

Contoh:

```js
const namaDepan = 'Tom'
const namaBelakang = 'Holland'

const namaLengkap = namaDepan + ' ' + namaBelakang
// jika kita tidak menambahkan spasi di tengah, maka akan menjadi TomHolland

console.log(namaLengkap) // Tom Holland
```

Yang terakhir, kita dapat menggunakan penambahan dan pengurangan untuk menaikan 1 atau mengurangkan 1.

```js
let increment = 1
increment++
console.log(increment) // Logs 2

let decrement = 5
decrement--
console.log(decrement) // Logs 4
```

---

#### Tugas

Log kedalam konsol dengan `console.log()` untuk menghitung jumbah variabel a, b, dan c.

---

#### Contoh Uji

1. ```js
   assert.equal(myFunction(1, 2, 3), 6)
   ```

2. ```js
   assert.equal(myFunction(5, 5, 5), 15)
   ```
