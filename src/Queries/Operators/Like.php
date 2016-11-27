<?php

namespace Rmtram\ArrayQuery\Queries\Operators;

/**
 * Class Like
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
class Like extends AbstractLike
{
    /**
     * @param string $key
     * @param string $val
     * @param array $row
     * @return bool
     */
    public function evaluate($key, $val, $row)
    {
        $expected = $this->finder->find($key, $row);
        if (is_null($expected)) {
            return false;
        }
        return $this->match($expected, $val);
    }
}