<?php

namespace Rmtram\ArrayQuery\Queries\Operators;
use Rmtram\ArrayQuery\Queries\Finders\FinderInterface;
use Rmtram\ArrayQuery\Queries\Finders\RecursiveFinder;

/**
 * Class AbstractLike
 * @package Rmtram\ArrayQuery\Queries\Operators
 */
abstract class AbstractLike implements OperatorInterface
{
    /**
     * @var string
     */
    private $literal = '%';

    /**
     * @var array
     */
    private $escapeCharacter = array(
        '\\', '/', '(',
        ')', '[', ']',
        '{', '}', '!',
        '.', '+', '-',
        '?', '*', '|',
        '$', '¥', '^'
    );

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

    /**
     * @param $expected
     * @param $actual
     * @return bool
     */
    protected function match($expected, $actual)
    {
        $forward = $this->sub($actual, 0, 1) !== $this->literal ? '^' : null;
        $backward  = $this->sub($actual, -1) !== $this->literal ? '$' : null;
        $actual = ltrim($actual, $this->literal);
        $actual = rtrim($actual, $this->literal);
        $pattern = sprintf('/%s%s%s/', $forward, $this->escape($actual), $backward);
        return !!preg_match($pattern, $expected);
    }

    /**
     * @param string $str
     * @return string
     */
    protected function escape($str)
    {
        foreach ($this->escapeCharacter as $char) {
            $str = str_replace($char, '\\' . $char, $str);
        }
        return $str;
    }

    /**
     * sub str
     * @param string $string
     * @param int $start
     * @param int|null $length
     * @param string|null $encoding
     * @return string
     */
    protected function sub($string, $start, $length = null, $encoding = null)
    {
        if(function_exists('mb_substr')) {
            if ($encoding) {
                return mb_substr($string, $start, $length, $encoding);
            }
            return mb_substr($string, $start, $length);
        }
        return substr($string, $start, $length);
    }
}