---
course: Javascript Basics
chapter: Conditionals
description: Learn the basics of Javascript, in here we will learn about conditionals if we need to branch some actions.
order: 6
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

## Chapter: Conditionals

---

In this chapter, conditionals means we can branch specific

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
