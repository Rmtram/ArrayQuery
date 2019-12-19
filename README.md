# ArrayQuery

This library provides ORM-like Array filtering.

## Contents
- Operations
    - [eq](#eq)
    - [notEq](#notEq)
    - [in](#in)
    - [notIn](#notIn)
    - [like](#like)
    - [notLike](#notLike)
    - [and](#and)
    - [or](#or)
    
## Methods

### eq

> Arguments
```
eq(string $key, mixed $val);
```

> Source code
```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'age' => 18],
]);
$aq->eq('id', 1)->all(); // [['id' => 1, 'age' => 18]]
$aq->eq('id', 1)->eq('age', 18)->all(); // [['id' => 1, 'age' => 18]]
$aq->eq('id', -1)->all(); // []
```

### notEq

> Arguments
```
notEq(string $key, mixed $val);
```

> Source code
```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'age' => 18],
    ['id' => 2, 'age' => 18],
]);
$aq->notEq('id', 1)->all(); // [['id' => 2, 'age' => 18]]
$aq->eq('id', 1)->notEq('age', 18)->all(); // []
$aq->notEq('id', -1)->all(); // [['id' => 1, 'age' => 18], ['id' => 2, 'age' => 18]]
```
