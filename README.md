# ArrayQuery

This library provides ORM-like Array filtering.

# Contents
- [Initializations](#Initializations)
    - [constructor](#constructor)
    - [reset](#reset)
- [Operations](#Operations)
    - [eq](#eq)
    - [notEq](#notEq)
    - [in](#in)
    - [notIn](#notIn)
    - [like](#like)
    - [notLike](#notLike)
    - [gt](#gt)
    - [gte](#gte)
    - [lt](#lt)
    - [lte](#lte)
    - [and](#and)
    - [or](#or)
- [Excecutions](#Executions)
    - [generator](#generator)
    - [all](#all)
    - [one](#one)
    - [count](#count)
    - [exists](#exists)
    - [map](#map)
- [Setters](#Setters)
    - [setDelimiter](#setDelimiter)
    - [setResettable](#setResettable)

# Methods

## Initializations

### constructor

> Arguments

```
constructor(array $items, bool $resettable = true);
```

> Source code

```php
// resettable = true (default)
$aq = new Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1],
    ['id' => 2],
]);

$aq->eq('id', 1)->count(); // 1
// state: eq('id', 2)->count();
$aq->eq('id', 2)->count(); // 1

// resettable = false
$aq = new Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1],
    ['id' => 2],
], false);

$aq->eq('id', 1)->count(); // 1
// state: eq('id', 1)->eq('id', 2)->count();
$aq->eq('id', 2)->count(); // 0
```

### reset

## Operations

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

### gt
`gt` is an alias for `greater than`, compare `a > b`.

> Arguments

```
gt(string $key, mixed $val)
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'age' => 15],
    ['id' => 2, 'age' => 16],
    ['id' => 3, 'age' => 17],
]);

$aq->gt('age', 14)->all(); // [['id' => 2, 'age' => 16], ['id' => 2, 'age' => 16], ['id' => 3, 'age' => 17]]
$aq->gt('age', 15)->all(); // [['id' => 2, 'age' => 16], ['id' => 3, 'age' => 17]]
$aq->gt('age', 16)->all(); // [['id' => 3, 'age' => 17]]
$aq->gt('age', 17)->all(); // []
```

### gte
`gte` is an alias for `greater equal than`, compare `a >= b`.

> Arguments

```
gte(string $key, mixed $val)
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'age' => 15],
    ['id' => 2, 'age' => 16],
    ['id' => 3, 'age' => 17],
]);

$aq->gte('age', 15)->all(); // [['id' => 2, 'age' => 16], ['id' => 2, 'age' => 16], ['id' => 3, 'age' => 17]]
$aq->gte('age', 16)->all(); // [['id' => 2, 'age' => 16], ['id' => 3, 'age' => 17]]
$aq->gte('age', 17)->all(); // [['id' => 3, 'age' => 17]]
$aq->gte('age', 18)->all(); // []
```

### lt
`lt` is an alias for `less than`, compare `a < b`.

> Arguments

```
lt(string $key, mixed $val)
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'age' => 15],
    ['id' => 2, 'age' => 16],
    ['id' => 3, 'age' => 17],
]);

$aq->lt('age', 15)->all(); // []
$aq->lt('age', 16)->all(); // [['id' => 3, 'age' => 17]]
$aq->lt('age', 17)->all(); // [['id' => 2, 'age' => 16], ['id' => 3, 'age' => 17]]
$aq->lt('age', 18)->all(); // [['id' => 2, 'age' => 16], ['id' => 2, 'age' => 16], ['id' => 3, 'age' => 17]]
```

### lte
`lte` is an alias for `less equal than`, compare `a < b`.

> Arguments

```
lte(string $key, mixed $val)
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'age' => 15],
    ['id' => 2, 'age' => 16],
    ['id' => 3, 'age' => 17],
]);

$aq->lte('age', 14)->all(); // []
$aq->lte('age', 15)->all(); // [['id' => 3, 'age' => 17]]
$aq->lte('age', 16)->all(); // [['id' => 2, 'age' => 16], ['id' => 3, 'age' => 17]]
$aq->lte('age', 17)->all(); // [['id' => 2, 'age' => 16], ['id' => 2, 'age' => 16], ['id' => 3, 'age' => 17]]
```

### and

> Arguments

```
and(callable $callback(\Rmtram\ArrayQuery\Queries\Where $where))
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'status' => 'active', 'age' => 18],
    ['id' => 2, 'status' => 'active', 'age' => 20],
    ['id' => 3, 'status' => 'active', 'age' => 19],
]);
$aq->eq('status', 'active')
    ->and(function (\Rmtram\ArrayQuery\Queries\Where $where) {
        $where->eq('id', 1)->or(function (\Rmtram\ArrayQuery\Queries\Where $where) {
            $where->eq('age', 19);
        });
    })->all(); 
    // [
    //     ['id' => 1, 'status' => 'active', 'age' => 18],
    //     ['id' => 3, 'status' => 'active', 'age' => 19]
    // ]
```

### or

> Arguments

```
or(callable $callback(\Rmtram\ArrayQuery\Queries\Where $where))
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'status' => 'active', 'age' => 18],
    ['id' => 2, 'status' => 'active', 'age' => 20],
    ['id' => 3, 'status' => 'active', 'age' => 19],
]);
$aq->eq('id', 1)
    ->or(function (\Rmtram\ArrayQuery\Queries\Where $where) {
        $where->eq('age', 20);
    })->all(); 
    // [
    //     ['id' => 1, 'status' => 'active', 'age' => 18],
    //     ['id' => 2, 'status' => 'active', 'age' => 20]
    // ]
```

## Executions

### generator

### all

### one

### count

### exists

### map
