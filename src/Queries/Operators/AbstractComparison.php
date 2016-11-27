<?php

namespace Rmtram\ArrayQuery\Queries\Operators;
use Rmtram\ArrayQuery\Exceptions\EmptyOperatorException;
use Rmtram\ArrayQuery\Exceptions\InvalidArgumentException;
use Rmtram\ArrayQuery\Queries\Finders\FinderInterface;

/**
 * Class AbstractComparison
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
abstract class AbstractComparison implements OperatorInterface
{
    /**
     * @var string
     */
    protected $operator;

    /**
     * @var FinderInterface
     */
    protected $finder;

    /**
     * @param FinderInterface $finder
     * @throws EmptyOperatorException
     */
    public function __construct(FinderInterface $finder)
    {
        if (empty($this->operator)) {
            throw new EmptyOperatorException(get_called_class());
        }
        $this->finder = $finder;
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
        $expected = $this->finder->find($key, $row);
        if (is_null($expected)) {
            return false;
        }
        return $this->compare($expected, $val, $this->operator);
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