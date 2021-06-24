---
course: Javascript Basics
chapter: Data Types
description: Learn the basics of Javascript, Javascript Data Types.
order: 3
function_name: types
initial_code: |
  function types(value){
    //edit code below

    //edit code above
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

# Javascript Basics

## Chapter: Data Types

---

Like many other programming languages, Javascript has its own Data Types.

Several of its basic Data Types :

- Number -> any numbers, integer, decimal, etc. The integer is limited to ( 2^53 - 1 )
- String -> any strings. e.g. "My Name is John. I am 20 years old."
- Boolean -> `true` / `false`

We can use the `typeof` operator to see the data type of a value;

example:

```js
let name = 'Hello'
typeof name // string

typeof 0 // number

typeof false // boolean
```

---

#### Task

Log to console with `console.log()` the type of the value passed to the function.

---

#### Example Tests

1. ```js
   assert.equal(types('Hello World'), 'string')
   ```

2. ```js
   assert.equal(types(10), 'number')
   ```
