---
course: Javascript Basics
chapter: Hello World
description: Learn to log hello world!
order: 1
function_name: func
initial_code: |
  function func(value){
    //edit code below

    //edit code above
  }
test_cases:
  - input:
      - '"Hello World"'
    expect: 'Hello World'
---

# Javascript Basics

## Chapter: Hello World

---

In this course you will learn to print to the console by Javascript;

In Javascript you can use the `console.log()` function to print your desired output to the console.
This function is an inbuilt function in Javascript.

example:

```js
console.log('Hello World!')
// logs Hello from Kodektif! to the console.
```

```js
const a = 'Kodektif'
console.log(a) // Logs Kodektif
```

---

#### Task

Log to console output any value passed to the function named `func`.

---

#### Example Tests

1. ```js
   assert.equal(func('Hello World'), 'Hello World')
   ```
