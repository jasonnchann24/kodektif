---
course: Javascript Basics
chapter: Maths & Operators
description: Learn the basics of Javascript, Maths & Operators in Javascript.
order: 4
function_name: myFunction
initial_code: |
  function myFunction(a,b,c){
    //edit code below

    //edit code above
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

# Javascript Basics

## Chapter: Maths & Operators

---

Operators in programming is just like basic maths, addition, substraction, multiplication, etc.

Javascript have these operations:

- Addition `+`
- Substraction `-`
- Multiplication `*`
- Division `/`
- Remainder `%`
- Exponentiation `**`

All of these operations is basically the same as maths at school, except the remainder and exponent.

Example of using remainder and exponentiation:

```js
const a = 10
const b = 3

console.log(a % b) // logs 1 (10 / 3 = 3 remainder 1)
console.log(a ** b) // logs 1000 (10³ = 1000)
```

In programming languages like Javascript, we can use `+` to concatenate strings

Example:

```js
const firstName = 'Tom'
const lastName = 'Holland'

const fullName = firstName + ' ' + lastName
// if we dont specify the space, it will be TomHolland

console.log(fullName) // Tom Holland
```

At last, we can use the addition and substraction for increment or decrement.

```js
let increment = 1
increment++
console.log(increment) // Logs 2

let decrement = 5
decrement--
console.log(decrement) // Logs 4
```

---

#### Task

Log to console with `console.log()` to sum the value of a, b and c.

---

#### Example Tests

1. ```js
   assert.equal(myFunction(1, 2, 3), 6)
   ```

2. ```js
   assert.equal(myFunction(5, 5, 5), 15)
   ```
