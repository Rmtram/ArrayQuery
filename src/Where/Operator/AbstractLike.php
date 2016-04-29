<?php

namespace Rmtram\ArrayQuery\Query\Operator;

/**
 * Class AbstractLike
 * @package Rmtram\ArrayQuery\Query\Operator
 */
abstract class AbstractLike implements OperatorInterface
{
    /**
     * @trait Existable
     */
    use Existable;

    /**
     * @var string
     */
    private $literal = '%';

    /**
     * @var array
     */
    private $escapeCharacter = [
        '\\', '/', '(',
        ')', '[', ']',
        '{', '}', '!',
        '.', '+', '-',
        '?', '*', '|',
        '$', '¥', '^'
    ];

    /**
     * @param string $key
     * @param string $val
     * @param array $row
     * @return bool
     */
    public function evaluate($key, $val, $row)
    {
        $forward  = $this->sub($val, -1) !== $this->literal ? '^' : null;
        $backward = $this->sub($val, 0, 1) !== $this->literal ? '$' : null;
        $val = ltrim($val, $this->literal);
        $val = rtrim($val, $this->literal);
        $pattern = sprintf('/%s%s%s/', $forward, $this->escape($val), $backward);
        return !!preg_match($pattern, $row[$key]);
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