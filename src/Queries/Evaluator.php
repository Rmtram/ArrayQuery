<?php
declare(strict_types=1);

namespace Rmtram\ArrayQuery\Queries;

use Rmtram\ArrayQuery\Queries\Finders\FinderInterface;
use Rmtram\ArrayQuery\Queries\Operators\OperatorInterface;

/**
 * Class Evaluator
 * @package Rmtram\ArrayQuery\Queries
 */
class Evaluator
{
    const EMPTY = -1;
    const NG = 0;
    const OK = 1;

    /**
     * @var FinderInterface
     */
    private $finder;

    private $operatorCaches = [];

    /**
     * Evaluator constructor.
     * @param FinderInterface $finder
     */
    public function __construct(FinderInterface $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @param Where $current
     * @param array $item
     * @return int
     */
    public function evaluates(Where $current, array $item): int
    {
        $cev = $this->evaluate($current, $item);

        $children = $current->getChildren();
        if (empty($children)) {
            return $cev;
        }

        $classify = $this->classify($children);

        // False if "OR" does not exist and "current" is NG.
        if (empty($classify[Where::LOGIC_OR]) && $cev === self::NG) {
            return self::NG;
        }

        $cev = $this->evaluateAndChildren($classify, $cev, $item);

        // "OR" will be "OK" if even one matches.
        return $this->evaluateOrChildren($item, $classify, $cev);
    }

    /**
     * @param Where $where
     * @param array $item
     * @return int
     */
    public function evaluate(Where $where, array $item): int
    {
        $wheres = $where();
        if (empty($wheres)) {
            return self::EMPTY;
        }
        foreach ($wheres as $w) {
            [$key, $val, $method] = $w;
            $operator = $this->getOperator($where, $method);
            if (!$operator->evaluate($key, $val, $item)) {
                return self::NG;
            }
        }
        return self::OK;
    }

    /**
     * @param Where $where
     * @param string $method
     * @return OperatorInterface
     */
    private function getOperator(Where $where, string $method): OperatorInterface
    {
        if (isset($this->operatorCaches[$method])) {
            return $this->operatorCaches[$method];
        }
        $operator = $where::OPERATOR_CLASSES[$method];
        $class = new $operator($this->finder);
        if (!$class instanceof OperatorInterface) {
            throw new \LogicException($method);
        }
        $this->operatorCaches[$method] = $class;
        return $class;
    }

    /**
     * @param array $children
     * @return array
     */
    public function classify(array $children): array
    {
        $classify = [Where::LOGIC_AND => [], Where::LOGIC_OR => []];
        /** @var Where $child */
        foreach ($children as $child) {
            $classify[$child->getLogicOperation()][] = $child;
        }
        return $classify;
    }

    /**
     * @param array $classify
     * @param int $currentEvaluated
     * @param array $item
     * @return int
     */
    private function evaluateAndChildren(array $classify, int $currentEvaluated, array $item): int
    {
        if (empty($classify[Where::LOGIC_AND]) || $currentEvaluated === self::NG) {
            return $currentEvaluated;
        }
        $evaluated = self::EMPTY;
        foreach ($classify[Where::LOGIC_AND] as $child) {
            $evaluated = $this->evaluates($child, $item);
            if ($evaluated === self::NG) {
                break;
            }
        }
        return $evaluated === self::NG ? $evaluated : $currentEvaluated;
    }

    /**
     * @param array $item
     * @param array $classify
     * @param int $currentEvaluated
     * @return int
     */
    private function evaluateOrChildren(array $item, array $classify, int $currentEvaluated): int
    {
        $ng = false;
        foreach ($classify[Where::LOGIC_OR] as $child) {
            $evaluated = $this->evaluates($child, $item);
            if ($evaluated === self::OK) {
                return $evaluated;
            }
            if ($evaluated === self::NG) {
                $ng = true;
            }
        }

        if ($currentEvaluated === self::OK || $currentEvaluated === self::NG) {
            return $currentEvaluated;
        }

        // If NG exists with "OR", it will be NG.
        return $ng ? self::NG : self::EMPTY;
    }
}
