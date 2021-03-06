<?php
declare(strict_types=1);

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

    /**
     * @param string $key
     * @param array $item
     * @return bool
     */
    public function existsKey(string $key, array $item): bool;
}
