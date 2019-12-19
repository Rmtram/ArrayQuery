<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery\Queries\Operators;

/**
 * Class In
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
class In extends AbstractComparison
{
    /**
     * @var string
     */
    protected $operator = 'in';
}
