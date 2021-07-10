---
course: Javascript Basics
chapter: Comparison & Logical Operators
description: Learn the basics of Javascript, in here we will learn Comparison & Logical Operators.
order: 5
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

# Javascript Basics

## Chapter: Comparison & Logical Operators

---

In this chapter we will learn the comparison & logical operators.

### Comparisons Operators

Available **comparison operators** in Javascript are :

- Greater / Less than `a > b` / `a < c`
- Greater / Less than or equals `a >= b` / `a <= c`
- Equals `a == b`
- Not equals `a != b` (using the ! and =)

The result of these comparison operators is `Boolean`

Example :

```js
const a = 5
const z = 10

const isLessThan = a < b
const isEquals = a == b
const isNotEquals = a != b

console.log(isLessThan) // true

console.log(isEquals) // false

console.log(isNotEquals) // true

// Comparison can be use also in strings

console.log('C' > 'A') // true
```

### Logical Operators

Logical operators are used when we want to compare between some of comparisons.

Available logical operators in Javascript are :

- And `&&`
- Or `||`
- Not `!`

Examples:

```js
const thisIsTrue = 1 + 5 == 6
const thisIsFalse = 1 > 2

console.log(thisIsTrue && thisIsFalse) // logs false

console.log(thisIsTrue || thisIsFalse) // logs true

console.log(!thisIsTrue) // logs false
```

---

#### Task

Log to console with `console.log()` with these rules.
a must be smaller than b, and c must be greater than a;

---

#### Example Tests

1. ```js
   assert.equal(myFunction(1, 2, 3), true)
   ```

2. ```js
   assert.equal(myFunction(1, 2, 0), false)
   // 1 is greater than 2 but 0 is smaller than 1 so it returns false
   ```
