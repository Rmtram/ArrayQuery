<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery;

use Rmtram\ArrayQuery\Queries\Evaluator;
use Rmtram\ArrayQuery\Queries\Finders\RecursiveFinder;
use Rmtram\ArrayQuery\Queries\Where;

/**
 * Class ArrayQuery
 * @package Rmtram\ArrayQuery
 * @method $this eq(string $key, mixed $val)
 * @method $this notEq(string $key, mixed $val)
 * @method $this in(string $key, array $val)
 * @method $this notIn(string $key, array $val)
 * @method $this gt(string $key, mixed $val)
 * @method $this gte(string $key, mixed $val)
 * @method $this lt(string $key, mixed $val)
 * @method $this lte(string $key, mixed $val)
 * @method $this like(string $key, string $val)
 * @method $this notLike(string $key, string $val)
 * @method $this null(string $key, bool $checkExistsKey = false)
 * @method $this notNull(string $key)
 */
class ArrayQuery
{
    const DEFAULT_DELIMITER = '.';

    /**
     * @var array
     */
    private $items;

    /**
     * @var Where
     */
    private $where;

    /**
     * @var bool
     */
    private $resettable;

    /**
     * @var string|null
     */
    private $delimiter = self::DEFAULT_DELIMITER;

    /**
     * @param array $items
     * @param bool $resettable
     */
    public function __construct(array $items, bool $resettable = true)
    {
        $this->where = new Where();
        $this->items = $items;
        $this->resettable = $resettable;
    }

    /**
     * @param bool $resettable
     * @return $this
     */
    public function setResettable(bool $resettable): self
    {
        $this->resettable = $resettable;
        return $this;
    }

    /**
     * @param string $delimiter
     * @return $this
     */
    public function setDelimiter(string $delimiter): self
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function and(callable $callback): self
    {
        $this->where->and($callback);
        return $this;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function or(callable $callback): self
    {
        $this->where->or($callback);
        return $this;
    }

    /**
     * @return \Generator
     */
    public function generator(): \Generator
    {
        $evaluator = new Evaluator(new RecursiveFinder($this->delimiter));
        foreach ($this->items as $item) {
            if ($evaluator->evaluates($this->where, $item) !== Evaluator::NG) {
                yield $item;
            }
        }
        if ($this->resettable) {
            $this->reset();
        }
    }

    /**
     * @return array|null
     */
    public function first(): ?array
    {
        return $this->generator()->current();
    }

    /**
     * @return array|null
     */
    public function last(): ?array
    {
        $generator = $this->generator();
        if (!$generator->valid()) {
            return null;
        }
        foreach ($generator as $item);
        return $item;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return iterator_to_array($this->generator());
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->all());
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->generator()->valid();
    }

    /**
     * @param callable $callback
     * @return array
     */
    public function map(callable $callback)
    {
        $items = [];
        foreach ($this->generator() as $item) {
            $items[] = $callback($item);
        }
        return $items;
    }

    /**
     * @param array $keys
     * @return array
     */
    public function pluck(array $keys): array
    {
        $keys = array_flip($keys);
        $items = [];
        foreach ($this->generator() as $item) {
            $items[] = array_intersect_key($item, $keys);
        }
        return $items;
    }

    /**
     * @param array $keys
     * @return array|null
     */
    public function pluckFirst(array $keys): ?array
    {
        $item = $this->first();
        return !is_null($item) ? array_intersect_key($item, array_flip($keys)) : null;
    }

    /**
     * @param array $keys
     * @return array|null
     */
    public function pluckLast(array $keys): ?array
    {
        $item = $this->last();
        return !is_null($item) ? array_intersect_key($item, array_flip($keys)) : null;
    }

    /**
     * @return $this
     */
    public function reset(): self
    {
        $this->where = new Where();
        return $this;
    }

    /**
     * @param string $method
     * @param array $args
     * @return $this
     */
    public function __call(string $method, array $args): self
    {
        $this->where->__call($method, $args);
        return $this;
    }
}
