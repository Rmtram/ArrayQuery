<?php

namespace Rmtram\ArrayQuery\Query\Operator;

/**
 * Interface OperatorInterface
 * @package Rmtram\ArrayQuery\Query\Operator
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