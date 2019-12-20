<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery\Queries\Operators;

use Rmtram\ArrayQuery\Exceptions\InvalidArgumentException;

/**
 * Interface OperatorInterface
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
interface OperatorInterface
{
    /**
     * Evaluate whether the row data matches.
     *
     * @param Parameter $parameter
     * @param array $item
     * @return bool
     */
    public function evaluate(Parameter $parameter, array $item): bool;
}
