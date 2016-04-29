<?php

namespace Rmtram\ArrayQuery;

use Rmtram\ArrayQuery\Query\Where;

class ArrayQuery
{

    /**
     * @var Where
     */
    private $where;

    /**
     * @var array
     */
    private $items;

    /**
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->where = new Where();
        $this->items = $items;
    }


}