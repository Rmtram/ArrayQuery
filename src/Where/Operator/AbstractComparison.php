<?php

namespace Rmtram\ArrayQuery\Query\Operator;
use Rmtram\ArrayQuery\Exceptions\EmptyOperatorException;
use Rmtram\ArrayQuery\Exceptions\InvalidArgumentException;

/**
 * Class AbstractComparison
 * @package Rmtram\ArrayQuery\Query\Operator
 */
abstract class AbstractComparison implements OperatorInterface
{
    /**
     * @trait Existable
     */
    use Existable;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @throws EmptyOperatorException
     */
    public function __construct()
    {
        if (empty($this->operator)) {
            throw new EmptyOperatorException(get_called_class());
        }
    }

    /**
     * @param string $key
     * @param mixed $val
     * @param array $row
     * @return bool
     * @throws InvalidArgumentException
     */
    public function evaluate($key, $val, $row)
    {
        if (!$this->exists($key, $row)) {
            return false;
        }
        return $this->compare($val, $row[$key], $this->operator);
    }


    /**
     * @param mixed $a
     * @param mixed $b
     * @param string $operator
     * @return bool
     * @throws InvalidArgumentException
     */
    public function compare($a, $b, $operator)
    {

        switch ($operator) {
            case '<':
                return $a < $b;
            case '<=':
                return $a <= $b;
            case '>':
                return $a > $b;
            case '>=':
                return $a >= $b;
            case '=':
                return $a === $b;
            case '!=':
                return $a !== $b;
            default:
                throw new InvalidArgumentException('invalid operator ' . $operator);
        }
    }

}