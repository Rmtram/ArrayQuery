<?php

namespace Rmtram\ArrayQuery;

use Rmtram\ArrayQuery\Exceptions\CallbackBreakException;
use Rmtram\ArrayQuery\Exceptions\InvalidArgumentException;
use Rmtram\ArrayQuery\Queries\Query;
use Rmtram\Sorter\Sorter;

/**
 * Class ArrayQuery
 * @package Rmtram\ArrayQuery
 */
class ArrayQuery extends Query
{

    /**
     * @var array
     */
    private $items;

    /**
     * @var array
     */
    private $order = array();

    /**
     * @param array $items
     */
    public function __construct(array $items)
    {
        parent::__construct();
        $this->items = $items;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function first()
    {
        $ret = null;
        try {
            $items = $this->items;
            if (!empty($this->order)) {
                $items = Sorter::make($items)->sort($this->order);
            }
            $this->walk($items, function($item) use(&$ret) {
                $ret = $item;
                throw new CallbackBreakException;
            });
            return $ret;
        } catch (CallbackBreakException $e) {
            return $ret;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param array|string $column
     * @param null|string $direction
     * @return $this
     * @throws InvalidArgumentException
     */
    public function order()
    {
        $args = func_get_args();
        if (empty($args[0])) {
            throw new InvalidArgumentException('invalid arguments of empty.');
        }

        if (is_array($args[0])) {
            foreach ($args[0] as $column => $direction) {
                $this->order[$column] = $direction;
            }
            return $this;
        }

        if (2 !== $len = count($args)) {
            throw new InvalidArgumentException('expects at 2 parameters, ' . $len . ' given.');
        }

        $this->order[$args[0]] = $args[1];
        return $this;
    }

    /**
     * @return array
     */
    public function all()
    {
        $items = array();
        $this->walk($this->items, function($item) use(&$items) {
            $items[] = $item;
        });
        return $this->sort($items);
    }

    /**
     * @param callable $callback
     * @return void
     */
    public function each(callable $callback)
    {
        $items = $this->sort($this->items);
        $this->walk($items, $callback);
    }

    /**
     * @param callable $callback
     * @return array
     */
    public function map(callable $callback)
    {
        $items = array();
        $this->walk($this->items, function($item) use($callback, &$items) {
            $items[] = $callback($item);
        });
        return $this->sort($items);
    }

    /**
     * @return int
     */
    public function count()
    {
        $count = 0;
        $this->walk($this->items, function() use(&$count) {
            $count++;
        });
        return $count;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function exists()
    {
        try {
            $this->walk($this->items, function() {
                throw new CallbackBreakException;
            });
            return false;
        } catch (CallbackBreakException $e) {
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return $this
     */
    public function reset()
    {
        $this->where = array();
        $this->order = array();
        $this->delimiter(self::DEFAULT_DELIMITER);
        return $this;
    }

    /**
     * @param array|null $items
     * @return array
     */
    private function sort($items = null)
    {
        if (empty($this->order)) {
            return $items;
        }
        return Sorter::make($items)->sort($this->order);
    }

}