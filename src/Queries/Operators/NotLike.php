<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery\Queries\Operators;

/**
 * Class NotLike
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
class NotLike extends AbstractLike
{
    /**
     * @param string $key
     * @param string $val
     * @param array $row
     * @return bool
     */
    public function evaluate(string $key, $val, array $row): bool
    {
        $expected = $this->finder->find($key, $row);
        if (is_null($expected)) {
            return false;
        }
        return !$this->match($expected, $val);
    }
}
