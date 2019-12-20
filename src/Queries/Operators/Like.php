<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery\Queries\Operators;

/**
 * Class Like
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
class Like extends AbstractLike
{
    /**
     * @param Parameter $parameter
     * @param array $item
     * @return bool
     */
    public function evaluate(Parameter $parameter, array $item): bool
    {
        $expected = $this->finder->find($parameter->getKey(), $item);
        if (is_null($expected)) {
            return false;
        }
        return $this->match($expected, $parameter->getVal());
    }
}
