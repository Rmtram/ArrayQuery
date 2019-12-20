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

### in

> Arguments
```
in(string $key, array $val);
```

> Source code
```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'age' => 18],
    ['id' => 2, 'age' => 18],
]);
$aq->in('id', [1])->all(); // [['id' => 1, 'age' => 18]]
$aq->in('id', [1, 2])->all(); // [['id' => 1, 'age' => 18], ['id' => 2, 'age' => 18]]
$aq->in('id', [2, 3])->all(); // [['id' => 2, 'age' => 18]]
$aq->in('id', [-1])->all(); // []
```

### notIn

> Arguments
```
notIn(string $key, array $val);
```

> Source code
```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'age' => 18],
    ['id' => 2, 'age' => 18],
]);
$aq->notIn('id', [1])->all(); // [['id' => 2, 'age' => 18]]
$aq->notIn('id', [1, 2])->all(); // []
$aq->notIn('id', [2, 3])->all(); // [['id' => 1, 'age' => 18]]
$aq->notIn('id', [-1])->all(); // [['id' => 1, 'age' => 18], ['id' => 2, 'age' => 18]]
```

### like

> Arguments
```
like(string $key, string $val);
```

> Source code
```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'name' => 'hoge'],
    ['id' => 2, 'name' => 'fuga'],
]);
$aq->like('name', 'h%')->all(); // [['id' => 1, 'name' => 'hoge']]
$aq->like('name', '%g%')->all(); // [['id' => 1, 'name' => 'hoge'], ['id' => 2, 'name' => 'fuga']]
$aq->like('name', 'nothing')->all(); // []
```

### notLike

> Arguments
```
notLike(string $key, string $val);
```

> Source code
```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'name' => 'hoge'],
    ['id' => 2, 'name' => 'fuga'],
]);
$aq->notLike('name', 'h%')->all(); // [['id' => 2, 'name' => 'fuga']]
$aq->notLike('name', '%g%')->all(); // []
$aq->notLike('name', 'nothing')->all(); // [['id' => 1, 'name' => 'hoge'], ['id' => 2, 'name' => 'fuga']]
```
