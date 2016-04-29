<?php

namespace Rmtram\ArrayQuery\Query;

use Rmtram\ArrayQuery\Exceptions\CallbackBreakException;

/**
 * Class Result
 * @package Rmtram\ArrayQuery\Query
 */
class Result
{
    /**
     * @var Where
     */
    private $where;

    /**
     * @param Where $where
     */
    public function __construct(Where $where)
    {
        $this->where = $where;
    }

    /**
     * get item
     * @return mixed
     * @throws \Exception
     */
    public function first()
    {
        $item = null;
        try {
            $this->call(function($index, $row) use(&$item) {
                $item = $row;
                throw new CallbackBreakException;
            });
            return $item;
        } catch (CallbackBreakException $e) {
            return $item;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function exists()
    {
        $exists = false;
        try {
            $this->call(function() {
                throw new CallbackBreakException;
            });
        } catch (CallbackBreakException $e) {
            $exists = true;
        } catch (\Exception $e) {
            throw $e;
        }
        return $exists;
    }

    /**
     * get count
     * @return int
     */
    public function count()
    {
        $count = 0;
        $this->call(function() use(&$count) {
            $count++;
        });
        return $count;
    }

    /**
     * get items.
     * @return array
     */
    public function get()
    {
        $items = [];
        $this->call(function($index, $row) use(&$items) {
            $items[] = $row;
        });
        return $items;
    }

    /**
     * @param callable $callable
     */
    public function call(callable $callable)
    {
        $where = $this->where;
        \Closure::bind(function() use($callable, $where) {
            $method = 'call';
            $where->$method(function($index, $row) use($callable) {
                $callable($index, $row);
            });
        }, $where, 'Rmtram\ArrayQuery\Query\Where')->__invoke();
    }

}