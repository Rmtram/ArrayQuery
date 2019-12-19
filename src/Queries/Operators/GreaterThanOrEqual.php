<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery\Queries\Operators;

/**
 * Class GreaterThanOrEqual
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
class GreaterThanOrEqual extends AbstractComparison
{
    /**
     * @var string
     */
    protected $operator = '>=';
}
