<?php

namespace Rmtram\ArrayQuery\Query\Operator;

/**
 * Class Existable
 * @package Rmtram\ArrayQuery\Query\Operator
 */
trait Existable
{
    /**
     * @param string $key
     * @param array $row
     * @return bool
     */
    protected function exists($key, $row)
    {
        return array_key_exists($key, $row);
    }
}