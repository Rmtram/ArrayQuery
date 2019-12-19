<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery\Queries\Operators;

/**
 * Class NotEqual
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
class NotEqual extends AbstractComparison
{
    /**
     * @var string
     */
    protected $operator = '!=';
}
