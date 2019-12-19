<?php

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
     * @return boolean
     */
    public function evaluate($key, $val, $row);
}
