<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery\Queries\Operators;

/**
 * Class Nil
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
class Nil implements OperatorInterface
{
    use Findable;

    /**
     * @param Parameter $parameter
     * @param array $item
     * @return bool
     */
    public function evaluate(Parameter $parameter, array $item): bool
    {
        $key = $parameter->getKey();
        $checkExistsKey = $parameter->getVal() === true;
        if ($checkExistsKey) {
            return $this->finder->existsKey($key, $item) && is_null($this->finder->find($key, $item));
        }
        return is_null($this->finder->find($key, $item));
    }
}
