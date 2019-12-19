<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery\Queries\Operators;

/**
 * Class LessThan
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
class LessThan extends AbstractComparison
{
    /**
     * @var string
     */
    protected $operator = '<';
}
