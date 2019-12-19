<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery\Queries\Finders;

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
     */
    public function __construct(string $delimiter = '.')
    {
        $this->delimiter($delimiter);
    }

    /**
     * @param $delimiter
     * @return $this
     */
    public function delimiter(string $delimiter): self
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * @param string $key
     * @param array $item
     * @return array|null
     */
    public function find(string $key, array $item)
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
     * @param string $key
     * @param array $item
     * @return mixed|null
     */
    private function pick(string $key, array $item)
    {
        return isset($item[$key]) ? $item[$key] : null;
    }
}
