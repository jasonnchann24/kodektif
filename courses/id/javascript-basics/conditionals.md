---
course: Javascript Basics
chapter: Conditionals
description: Learn the basics of Javascript, in here we will learn about conditionals if we need to branch some actions.
order: 6
function_name: myFunction
initial_code: |
  function myFunction(a,b,c){
    //ubah kode di bawah ini

    //ubah kode di atas ini
  }
test_cases:
  - input:
      - true
    expect: 'yes'
  - input:
      - false
    expect: 'no'
---

# Javascript Dasar

## Bagian: Kondisional

---

In this chapter, conditionals means we can branch specific conditions that meets certain conditions, and do something specifically to each condition. We use `if` and `else` in Javascript.
Pada bagian ini, kondisional berarti kita dapat melakukan cabang secara spesifik, dan melakukan sesuatu tergantung pada kondisi tersebut. Kita dapat menggunakan `if` dan `else` dalam Javascript.

Contoh:

```js
const apakahIniBenarAtauSalah = 1 > 5

if(apakahIniBenarAtauSalah){
  console.log('Ini benar')
}else{
  console.log('Ini salah')
}
// logs Ini salah
```

---

#### Tugas

Log ke dalam konsol dengan `console.log()` dengan peraturan berikut.
Jika variabel `a` adalah `true` maka log "yes", jika `false` "no".

---

#### Contoh Uji

1. ```js
   assert.equal(myFunction(false), "no")
   ```

2. ```js
   assert.equal(myFunction(true), "yes")
   ```
