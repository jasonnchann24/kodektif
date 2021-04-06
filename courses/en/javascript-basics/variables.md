---
course: Javascript Basics
chapter: Variables
description: Learn the basics of javascript, Javascript Variables.
order: 1
function_name: variable
initial_code: |
  function variable(value){
    //edit code below

    //edit code above
  }
test_cases:
  - input:
      - '"Hello World"'
    expect: 'Hello World'
  - input:
      - 10
    expect: 10
---

# Javascript Basics

## Chapter: Variables

---

You can define javascript variables with `let` / `const`

example:

```js
let name = 'Hello'
const price = 10
```

The difference between `let` & `const` is `let` can be reassigned while `const` cannot be reassigned.

```js
let value = 1
value = 2
console.log(value) // returns 2

const discount = 0.5
discount = 1 // will be error
```

---

#### Task

Log to console output from the variable you created. The variable must store the value from the function argument.

---

#### Example Tests

1. ```js
   assert.equal(variable('Hello World'), 'Hello World')
   ```

2. ```js
   assert.equal(variable(10), 10)
   ```
