---
course: Javascript Basics
chapter: Comparison & Logical Operators
description: Learn the basics of Javascript, in here we will learn Comparison & Logical Operators.
order: 5
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
    expect: true
  - input:
      - 1
      - 2
      - 0
    expect: false
  - input:
      - 1
      - 0
      - 3
    expect: false
---

# Javascript Dasar

## Bagian: Perbandingan & Operator Logis

---

Dalam bagian ini, kita akan mempelajari perbandingan & operator logis.

### Operator Perbandingan

Operator perbandingan yang tersedia dalam Javascript adalah :

- Lebih besar / lebih kecil `a > b` / `a < c`
- Lebih besar / lebih kecil sama dengan `a >= b` / `a <= c`
- Sama dengan `a == b`
- Tidak sama dengan `a != b` (menggunakan tanda ! dan =)

Hasil dari operasi perbandingan ini merupakan `Boolean`

Contoh :

```js
const a = 5
const z = 10

const lebihKecilDari = a < b
const samaDengan = a == b
const tidakSamaDengan = a != b

console.log(lebihKecilDari) // true

console.log(samaDengan) // false

console.log(tidakSamaDengan) // true

// Perbandingan juga dapat digunakan pada string

console.log('C' > 'A') // true
```

### Operator Logis

Operator logis digunakan jika kita ingin melakukan perbandingan antara perbandingan tertentu.

Operator logis yang tersedia pada Javascript :

- Dan `&&`
- Atau `||`
- Tidak `!`

Contoh:

```js
const iniBenar = 1 + 5 == 6
const iniSalah = 1 > 2

console.log(iniBenar && iniSalah) // logs false

console.log(iniBenar || iniSalah) // logs true

console.log(!iniBenar) // logs false
```

---

#### Tugas

Log ke dalam konsol dengan `console.log()` dengan peraturan sebagai berikut. Variabel a harus lebih kecil dari b dan c harus lebih besar dari a

---

#### Example Tests

1. ```js
   assert.equal(myFunction(1, 2, 3), true)
   ```

2. ```js
   assert.equal(myFunction(1, 2, 0), false)
   // 1 is greater than 2 but 0 is smaller than 1 so it returns false
   ```
