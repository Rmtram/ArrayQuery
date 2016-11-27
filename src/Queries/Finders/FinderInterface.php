<?php

namespace Rmtram\ArrayQuery\Queries\Finders;

/**
 * Interface FinderInterface
 * @package Rmtram\ArrayQuery\Queries\Finders
 */
interface FinderInterface {

    /**
     * @param $key
     * @param array $item
     * @return mixed
     */
    public function find($key, array $item);

}