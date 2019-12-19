<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery\Queries\Operators;

/**
 * Interface OperatorInterface
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
interface OperatorInterface
{
    /**
     * Evaluate whether the row data matches.
     * @param string $key
     * @param $val
     * @param $row
     * @return bool
     */
    public function evaluate(string $key, $val, array $row): bool;
}
