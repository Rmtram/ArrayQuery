<?php

namespace Rmtram\ArrayQuery\Queries\Finders;
use Rmtram\ArrayQuery\Exceptions\InvalidArgumentException;

/**
 * Class RecursiveFinder
 * @package Rmtram\ArrayQuery\Queries\Finders
 */
class RecursiveFinder implements FinderInterface
{

    /**
     * @var string
     */
    private $delimiter;

    /**
     * @param string $delimiter
     * @throws \Rmtram\ArrayQuery\Exceptions\InvalidArgumentException
     */
    public function __construct($delimiter = '.')
    {
        $this->delimiter($delimiter);
    }

    /**
     * @param $delimiter
     * @return $this
     * @throws \Rmtram\ArrayQuery\Exceptions\InvalidArgumentException
     */
    public function delimiter($delimiter)
    {
        if (!is_string($delimiter)) {
            throw new InvalidArgumentException('invalid variable type, delimiter is only string.');
        }
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * @param $key
     * @param array $item
     * @return mixed
     */
    public function find($key, array $item)
    {
        if (empty($item)) {
            return null;
        }

        if (false === strpos($key, $this->delimiter)) {
            return $this->pick($key, $item);
        }

        $keys = explode($this->delimiter, $key);

        foreach ($keys as $k) {
            if (null === $item = $this->pick($k, $item)) {
                return $item;
            }
        }

        return $item;
    }

    /**
     * @param $key
     * @param $item
     * @return mixed
     */
    private function pick($key, $item)
    {
        return isset($item[$key]) ? $item[$key] : null;
    }

}