[![Build Status](https://travis-ci.org/Rmtram/ArrayQuery.svg)](https://travis-ci.org/Rmtram/ArrayQuery)

# ArrayQuery

This library provides ORM-like Array filtering.

## Install

```
$ composer require rmtram/array-query
```

# Usage

```php
use Rmtram\ArrayQuery\ArrayQuery;
use Rmtram\ArrayQuery\Queries\Where;

$aq = new ArrayQuery([
    [
        'id' => 1,
        'name' => 'hoge',
        'blog' => [
            'title' => 'hoge blog',
            'category' => 'programming',
            'url'   => '#hoge',
            'end_date' => '2010-10-10',
        ]
    ],
    [
        'id' => 2,
        'name' => 'fuga',
        'blog' => [
            'title' => 'fuga blog',
            'category' => 'anime',
            'url'   => '#fuga',
            'end_date' => null
        ]
    ],
        [
        'id' => 3,
        'name' => 'piyo',
        'blog' => [
            'title' => 'piyo blog',
            'category' => 'anime',
            'url'   => '#piyo',
            'end_date' => '2010-10-14',

        ]
    ],
]);

$results = $aq->in('blog.category', ['anime', 'programming'])
    ->and(function (Where $where) {
        $where->eq('blog.end_date', '2010-10-10')->or(function (Where $where) {
            $where->null('blog.end_date');
        });
    })
    ->all(); // [['id' => 1, ...], ['id' => 2, ...]]
```

# Contents
- [Initializations](#Initializations)
    - [constructor](#constructor)
    - [reset](#reset)
- [Operations](#Operations)
    - [eq](#eq)
    - [notEq](#notEq)
    - [in](#in)
    - [notIn](#notIn)
    - [null](#null)
    - [notNull](#notNull)
    - [like](#like)
    - [notLike](#notLike)
    - [gt](#gt)
    - [gte](#gte)
    - [lt](#lt)
    - [lte](#lte)
    - [and](#and)
    - [or](#or)
- [Executions](#Executions)
    - [generator](#generator)
    - [all](#all)
    - [first](#first)
    - [last](#last)
    - [count](#count)
    - [exists](#exists)
    - [map](#map)
    - [pluck](#pluck)
    - [pluckFirst](#pluckFirst)
    - [pluckLast](#pluckLast)
- [Setters](#Setters)
    - [setDelimiter](#setDelimiter)
    - [setResettable](#setResettable)

# Methods

## Initializations

### constructor

> Definition

```
constructor(array $items, bool $resettable = true)
```

> Source code

```php
// resettable = true (default)
$aq = new Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1],
    ['id' => 2],
]);

$aq->eq('id', 1)->count(); // 1

// state: eq('id', 2)
$aq->eq('id', 2)->count(); // 1

// resettable = false
$aq = new Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1],
    ['id' => 2],
], false);

$aq->eq('id', 1)->count(); // 1

// state: eq('id', 1)->eq('id', 2)
$aq->eq('id', 2)->count(); // 0
```

### reset

> Definition

```
reset(): self
```

> Source code

```php
// resettable = false
$aq = new Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1],
    ['id' => 2],
], false);

$aq->eq('id', 1)->count(); // 1

// state: eq('id', 1)->eq('id', 2)
$aq->eq('id', 2)->count(); // 0

// state: eq('id', 2)
$aq->reset()->eq('id', 2)->count(); // 1
```

## Operations

### eq

> Definition

```
eq(string $key, mixed $val): self
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

> Definition

```
notEq(string $key, mixed $val): self
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

> Definition

```
in(string $key, array $val): self
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

> Definition

```
notIn(string $key, array $val): self
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

### null

> Definition

```
null(string $key, bool $checkExistsKey = false): self
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'address' => null],
    ['id' => 2],
    ['id' => 3, 'address' => 'x'],
]);

$aq->null('address')->all(); // [['id' => 1, 'address' => null], ['id' => 2]]
$aq->null('address', true)->all(); // [['id' => 1, 'address' => null]]
```

### notNull

> Definition

```
notNull(string $key): self
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'address' => null],
    ['id' => 2],
    ['id' => 3, 'address' => 'x'],
]);

$aq->notNull('address')->all(); // [['id' => 3, 'address' => 'x']]
```

### like

> Definition

```
like(string $key, string $val): self
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

> Definition

```
notLike(string $key, string $val): self
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

> Definition

```
gt(string $key, mixed $val): self
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

> Definition

```
gte(string $key, mixed $val): self
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

> Definition

```
lt(string $key, mixed $val): self
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

> Definition

```
lte(string $key, mixed $val): self
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

> Definition

```
and(callable $callback(\Rmtram\ArrayQuery\Queries\Where $where)): self
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

> Definition

```
or(callable $callback(\Rmtram\ArrayQuery\Queries\Where $where)): self
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

> Definition

```
generator(): Generator
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1],
    ['id' => 2],
]);

$generator = $aq->eq('id', 1)->generator();

// (Generator)[['id' => 1]]
foreach ($generator as $item) {
    echo $item['id']; // 1
}
```

### all

> Definition

```
all(): array
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1],
    ['id' => 2],
]);

$aq->all(); // (array)[['id' => 1], ['id' => 2]]
$aq->eq('id', 2)->all(); // (array)[['id' => 2]]
```

### first

> Definition

```
first(): ?array
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1],
    ['id' => 2],
]);

$aq->first(); // (array)['id' => 1]
$aq->eq('id', 2)->first(); // (array)['id' => 2]
$aq->eq('id', 3)->first(); // null
```

### last

> Definition

```
last(): ?array
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1],
    ['id' => 2],
]);

$aq->last();  // (array)['id' => 2]
$aq->eq('id', 3)->last();  // null
```

### count

> Definition

```
count(): int
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1],
    ['id' => 2],
]);

$aq->count(); // 2
$aq->eq('id', 2)->count(); // 1
$aq->eq('id', 3)->count(); // 0
```

### exists

> Definition

```
exists(): bool
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1],
    ['id' => 2],
]);

$aq->exists(); // true
$aq->eq('id', 2)->exists(); // true
$aq->eq('id', 3)->exists(); // false
```

### map

> Definition

```
map(callable $callback(array $item)): array
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1],
    ['id' => 2],
]);

$aq->map(function(array $item) {
    return $item['id'];
}); // [1, 2]

$aq->eq('id', 1)->map(function(array $item) {
    return $item['id'];
}); // [1]

$aq->eq('id', 3)->map(function(array $item) {
    return $item['id'];
}); // []
```

### pluck

> Definition

```
pluck(array $keys): array
```

> Source code

```php
$aq = new Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'age' => 1, 'address' => 'a'],
    ['id' => 2, 'age' => 2, 'address' => 'b'],
    ['id' => 3, 'age' => 3, 'address' => 'c'],
]);

$aq->pluck(['id', 'age']); // [['id' => 1, 'age' => 1], ['id' => 2, 'age' => 2], ['id' => 3, 'age' => 3]]
```

### pluckFirst

> Definition

```
pluckFirst(array $keys): ?array
```

> Source code

```php
$aq = new Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'age' => 1, 'address' => 'a'],
    ['id' => 2, 'age' => 2, 'address' => 'b'],
    ['id' => 3, 'age' => 3, 'address' => 'c'],
]);

$aq->pluckFirst(['id', 'age']); // ['id' => 1, 'age' => 1]
$aq->eq('id', -1)->pluckFirst(['id', 'age']); // null
```

### pluckLast

> Definition

```
pluckLast(array $keys): ?array
```

> Source code

```php
$aq = new Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'age' => 1, 'address' => 'a'],
    ['id' => 2, 'age' => 2, 'address' => 'b'],
    ['id' => 3, 'age' => 3, 'address' => 'c'],
]);

$aq->pluckLast(['id', 'age']); // ['id' => 3, 'age' => 3]
$aq->eq('id', -1)->pluckFirst(['id', 'age']); // null
```

## Setters

### setDelimiter

> Definition

```
setDelimiter(string $delimiter): self
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1, 'options' => ['address' => 'x']],
    ['id' => 2],
]);

$aq->eq('options.address', 'x')->exists(); // true

$aq->setDelimiter('@');
$aq->eq('options@address', 'x'); // true;
```

### setResettable

> Definition

```
setResettable(bool $resettable): self
```

> Source code

```php
$aq = new \Rmtram\ArrayQuery\ArrayQuery([
    ['id' => 1],
    ['id' => 2],
]);

$aq->eq('id', 1)->count(); // 1

// state: eq('id', 2)
$aq->eq('id', 2)->count(); // 1

$aq->setResettable(false);

$aq->eq('id', 1)->count(); // 1

// state: eq('id', 1)->eq('id', 2)
$aq->eq('id', 2)->count(); // 0
```
