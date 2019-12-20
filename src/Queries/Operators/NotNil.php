<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery\Queries\Operators;

use Rmtram\ArrayQuery\Exceptions\InvalidArgumentException;

/**
 * Class NotNil
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
class NotNil implements OperatorInterface
{
    use Findable;

    /**
     * @param Parameter $parameter
     * @param array $item
     * @return bool
     */
    public function evaluate(Parameter $parameter, array $item): bool
    {
        return is_null($this->finder->find($parameter->getKey(), $item)) === false;
    }
}
