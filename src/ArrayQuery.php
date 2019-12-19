<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery;

use Rmtram\ArrayQuery\Exceptions\InvalidArgumentException;
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
 * @method $this gt(string $key, int $val)
 * @method $this gte(string $key, int $val)
 * @method $this lt(string $key, int $val)
 * @method $this lte(string $key, int $val)
 * @method $this like(string $key, string $val)
 * @method $this notLike(string $key, string $val)
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
     * @var string|null
     */
    private $delimiter = self::DEFAULT_DELIMITER;

    /**
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->where = new Where();
        $this->items = $items;
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
    }

    /**
     * @return array|null
     */
    public function one(): ?array
    {
        return $this->generator()->current();
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return iterator_to_array($this->generator());
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
     * @throws InvalidArgumentException
     */
    public function __call(string $method, array $args): self
    {
        $this->where->__call($method, $args);
        return $this;
    }
}
