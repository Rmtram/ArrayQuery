<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery\Queries\Operators;

/**
 * Class Parameter
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
class Parameter
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var mixed
     */
    private $val;
    /**
     * @var string
     */
    private $method;

    /**
     * Parameter constructor.
     *
     * @param string $method
     * @param string $key
     * @param null $val
     */
    public function __construct(string $method, string $key, $val = null)
    {
        $this->method = $method;
        $this->key = $key;
        $this->val = $val;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return null
     */
    public function getVal()
    {
        return $this->val;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }
}
