<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery\Queries\Operators;

use Rmtram\ArrayQuery\Queries\Finders\FinderInterface;

/**
 * Trait Findable
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
trait Findable
{
    /**
     * @var FinderInterface
     */
    protected $finder;

    /**
     * @param FinderInterface $finder
     */
    public function __construct(FinderInterface $finder)
    {
        $this->finder = $finder;
    }
}
