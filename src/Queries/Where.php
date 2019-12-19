<?php

namespace Rmtram\ArrayQuery\Queries;

use Rmtram\ArrayQuery\Exceptions\InvalidArgumentException;
use Rmtram\ArrayQuery\Queries\Operators\Equal;
use Rmtram\ArrayQuery\Queries\Operators\GreaterThan;
use Rmtram\ArrayQuery\Queries\Operators\GreaterThanOrEqual;
use Rmtram\ArrayQuery\Queries\Operators\In;
use Rmtram\ArrayQuery\Queries\Operators\LessThan;
use Rmtram\ArrayQuery\Queries\Operators\LessThanOrEqual;
use Rmtram\ArrayQuery\Queries\Operators\Like;
use Rmtram\ArrayQuery\Queries\Operators\NotEqual;
use Rmtram\ArrayQuery\Queries\Operators\NotIn;
use Rmtram\ArrayQuery\Queries\Operators\NotLike;

/**
 * Class Where
 * @package Rmtram\ArrayQuery\Queries
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
class Where
{
    const LOGIC_OR = 'or';
    const LOGIC_AND = 'and';

    /**
     * @var string
     */
    private $logicOperation;

    /**
     * @var array
     */
    private $children = [];

    /**
     * @var array
     */
    private $wheres = [];

    /**
     * @var array
     */
    const OPERATOR_CLASSES = [
        'eq' => Equal::class,
        'notEq' => NotEqual::class,
        'gt' => GreaterThan::class,
        'gte' => GreaterThanOrEqual::class,
        'lt' => LessThan::class,
        'lte' => LessThanOrEqual::class,
        'like' => Like::class,
        'notLike' => NotLike::class,
        'in' => In::class,
        'notIn' => NotIn::class
    ];

    /**
     * Where constructor.
     *
     * @param string $logicOperation
     */
    public function __construct(string $logicOperation = self::LOGIC_AND)
    {
        $this->logicOperation = $logicOperation;
    }

    /**
     * Get child wheres
     *
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function getLogicOperation(): string
    {
        return $this->logicOperation;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function and(callable $callback): self
    {
        $child = new self(self::LOGIC_AND);
        $callback($child);
        $this->children[] = $child;
        return $child;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function or(callable $callback): self
    {
        $child = new self(self::LOGIC_OR);
        $callback($child);
        $this->children[] = $child;
        return $child;
    }

    /**
     * @param string $name
     * @param array $args
     * @return $this
     * @throws InvalidArgumentException
     */
    public function __call(string $name, array $args)
    {
        $count = count($args);
        if ($count !== 2) {
            throw new InvalidArgumentException(sprintf(
                'expects at 2 parameters, %d given',
                $count
            ));
        }
        if (!isset(self::OPERATOR_CLASSES[$name])) {
            throw new \BadMethodCallException('undefined method at ' . $name);
        }
        $this->wheres[] = [$args[0], $args[1], $name];
        return $this;
    }

    /**
     * @return array
     */
    public function __invoke(): array
    {
        return $this->wheres;
    }
}