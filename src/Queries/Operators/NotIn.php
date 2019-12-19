<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery\Queries\Operators;

/**
 * Class NotIn
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
class NotIn extends AbstractComparison
{
    /**
     * @var string
     */
    protected $operator = '!in';
}
