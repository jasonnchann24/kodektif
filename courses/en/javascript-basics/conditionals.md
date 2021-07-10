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
      - true
    expect: 'yes'
  - input:
      - false
    expect: 'no'
---

# Javascript Basics

## Chapter: Conditionals

---

In this chapter, conditionals means we can branch specific conditions that meets certain conditions, and do something specifically to each condition. We use `if` and `else` in Javascript.

Example:

```js
const isThisTrueOrFalse = 1 > 5

if(isThisTrueOrFalse){
  console.log('This is true')
}else{
  console.log('This is false')
}
// logs This is false
```

---

#### Task

Log to console with `console.log()` with these rules.
If variable a is true then logs "yes", if false "no".

---

#### Example Tests

1. ```js
   assert.equal(myFunction(false), "no")
   ```

2. ```js
   assert.equal(myFunction(true), "yes")
   ```
