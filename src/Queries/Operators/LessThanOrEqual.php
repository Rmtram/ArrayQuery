<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery\Queries\Operators;

/**
 * Class LessThanOrEqual
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
class LessThanOrEqual extends AbstractComparison
{
    /**
     * @var string
     */
    protected $operator = '<=';
}
