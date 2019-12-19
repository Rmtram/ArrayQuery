<?php

namespace Rmtram\ArrayQuery\Queries\Finders;

/**
 * Interface FinderInterface
 * @package Rmtram\ArrayQuery\Queries\Finders
 */
interface FinderInterface
{
    /**
     * @param string $key
     * @param array $item
     * @return array|null
     */
    public function find(string $key, array $item);
}
