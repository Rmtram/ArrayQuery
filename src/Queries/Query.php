<?php

namespace Rmtram\ArrayQuery\Queries;

use Rmtram\ArrayQuery\Exceptions\InvalidArgumentException;
use Rmtram\ArrayQuery\Exceptions\NotOperatorClassException;
use Rmtram\ArrayQuery\Exceptions\UndefinedOperatorClassException;
use Rmtram\ArrayQuery\Queries\Finders\FinderInterface;
use Rmtram\ArrayQuery\Queries\Finders\RecursiveFinder;
use Rmtram\ArrayQuery\Queries\Operators\OperatorInterface;

/**
 * Class Where
 * @package Rmtram\ArrayQuery\Queries
 * @method $this eq(string $key, mixed $val)
 * @method $this orEq(string $key, mixed $val)
 * @method $this notEq(string $key, mixed $val)
 * @method $this orNotEq(string $key, mixed $val)
 * @method $this gt(string $key, int $val)
 * @method $this orGt(string $key, int $val)
 * @method $this gte(string $key, int $val)
 * @method $this orGte(string $key, int $val)
 * @method $this lt(string $key, int $val)
 * @method $this orLt(string $key, int $val)
 * @method $this lte(string $key, int $val)
 * @method $this orLte(string $key, int $val)
 * @method $this like(string $key, string $val)
 * @method $this orLike(string $key, string $val)
 * @method $this notLike(string $key, string $val)
 * @method $this orNotLike(string $key, string $val)
 */
class Query
{

    const OPERATOR_EQUAL                 = 0;

    const OPERATOR_NOT_EQUAL             = 1;

    const OPERATOR_LIKE                  = 2;

    const OPERATOR_NOT_LIKE              = 3;

    const OPERATOR_GREATER_THAN          = 4;

    const OPERATOR_GREATER_THAN_OR_EQUAL = 5;

    const OPERATOR_LESS_THAN             = 6;

    const OPERATOR_LESS_THAN_OR_EQUAL    = 7;

    const COMPARE_AND = 0;

    const COMPARE_OR  = 1;

    const DEFAULT_DELIMITER = '.';


    /**
     * @var array
     */
    protected $where = array();

    /**
     * @var FinderInterface
     */
    protected $finder;

    /**
     * @var array
     */
    private $operators = array(
        self::OPERATOR_EQUAL                 => 'Rmtram\ArrayQuery\Queries\Operators\Equal',
        self::OPERATOR_NOT_EQUAL             => 'Rmtram\ArrayQuery\Queries\Operators\NotEqual',
        self::OPERATOR_LIKE                  => 'Rmtram\ArrayQuery\Queries\Operators\Like',
        self::OPERATOR_NOT_LIKE              => 'Rmtram\ArrayQuery\Queries\Operators\NotLike',
        self::OPERATOR_LESS_THAN             => 'Rmtram\ArrayQuery\Queries\Operators\LessThan',
        self::OPERATOR_LESS_THAN_OR_EQUAL    => 'Rmtram\ArrayQuery\Queries\Operators\LessThanOrEqual',
        self::OPERATOR_GREATER_THAN          => 'Rmtram\ArrayQuery\Queries\Operators\GreaterThan',
        self::OPERATOR_GREATER_THAN_OR_EQUAL => 'Rmtram\ArrayQuery\Queries\Operators\GreaterThanOrEqual'
    );

    /**
     * @var array
     */
    private $methods = array(
        'eq'        => array(self::OPERATOR_EQUAL,                 self::COMPARE_AND),
        'notEq'     => array(self::OPERATOR_NOT_EQUAL,             self::COMPARE_AND),
        'gt'        => array(self::OPERATOR_GREATER_THAN,          self::COMPARE_AND),
        'gte'       => array(self::OPERATOR_GREATER_THAN_OR_EQUAL, self::COMPARE_AND),
        'lt'        => array(self::OPERATOR_LESS_THAN,             self::COMPARE_AND),
        'lte'       => array(self::OPERATOR_LESS_THAN_OR_EQUAL,    self::COMPARE_AND),
        'like'      => array(self::OPERATOR_LIKE,                  self::COMPARE_AND),
        'notLike'   => array(self::OPERATOR_NOT_LIKE,              self::COMPARE_AND),
        'orEq'      => array(self::OPERATOR_EQUAL,                 self::COMPARE_OR),
        'orNotEq'   => array(self::OPERATOR_NOT_EQUAL,             self::COMPARE_OR),
        'orGt'      => array(self::OPERATOR_GREATER_THAN,          self::COMPARE_OR),
        'orGte'     => array(self::OPERATOR_GREATER_THAN_OR_EQUAL, self::COMPARE_OR),
        'orLt'      => array(self::OPERATOR_LESS_THAN,             self::COMPARE_OR),
        'orLte'     => array(self::OPERATOR_LESS_THAN_OR_EQUAL,    self::COMPARE_OR),
        'orLike'    => array(self::OPERATOR_LIKE,                  self::COMPARE_OR),
        'orNotLike' => array(self::OPERATOR_NOT_LIKE,              self::COMPARE_OR)
    );

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->finder = new RecursiveFinder(self::DEFAULT_DELIMITER);
    }

    /**
     * @param $delimiter
     * @return $this
     * @throws InvalidArgumentException
     */
    public function delimiter($delimiter)
    {
        $this->finder->delimiter($delimiter);
        return $this;
    }

    /**
     * @param $callName
     * @param $args
     * @return $this
     * @throws InvalidArgumentException
     */
    public function __call($callName, $args)
    {
        $count = count($args);
        if ($count !== 2) {
            throw new InvalidArgumentException(sprintf(
                'expects at 2 parameters, %d given',
                $count
            ));
        }
        if (!isset($this->methods[$callName])) {
            throw new \BadMethodCallException('undefined method at ' . $callName);
        }
        $method = $this->methods[$callName];
        $this->where[] = array($args[0], $args[1], $method[0], $method[1]);
        return $this;
    }

    /**
     * @param array $items
     * @param callable $callable
     * @throws NotOperatorClassException
     * @throws UndefinedOperatorClassException
     */
    protected function walk(array $items, callable $callable)
    {
        foreach ($items as $item) {
            $bool = true;
            foreach ($this->where as $where) {
                $operator = $this->getOperator($where[2]);
                $compare = $where[3];
                if ($operator->evaluate($where[0], $where[1], $item)) {
                    if (self::COMPARE_OR === $compare) {
                        $bool = true;
                        break;
                    }
                } else {
                    $bool = false;
                }
            }
            if (true === $bool) {
                $callable($item);
            }
        }
    }

    /**
     * @param int $operatorNumber
     * @return OperatorInterface
     * @throws NotOperatorClassException
     * @throws UndefinedOperatorClassException
     */
    private function getOperator($operatorNumber)
    {
        if (!isset($this->operators[$operatorNumber])) {
            throw new UndefinedOperatorClassException('undefined operator => ' . $operatorNumber);
        }
        $operator = $this->operators[$operatorNumber];
        if (!$operator instanceof OperatorInterface) {
            $operatorClassName = $operator;
            $operator = new $operator($this->finder);
            if (!$operator instanceof OperatorInterface) {
                throw new NotOperatorClassException($operatorClassName);
            }
            $this->operators[$operatorNumber] = $operator;
        }
        return $operator;
    }

}