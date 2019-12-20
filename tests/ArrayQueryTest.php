<?php

namespace Rmtram\ArrayQuery\Tests;

use Rmtram\ArrayQuery\ArrayQuery;
use Rmtram\ArrayQuery\Queries\Where;

class ArrayQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var array
     */
    private $fixtures = [];

    public function setUp(): void
    {
        $this->fixtures['users'] = require __DIR__ . '/fixtures/users.php';
    }

    public function testAll()
    {
        $this->assertEquals($this->fixtures['users'], $this->query()->all());
    }

    public function testAllToEmptyResult()
    {
        $actual = $this->query()->eq('id', -1)->all();
        $this->assertEquals(array(), $actual);
    }

    public function testFirst()
    {
        $actual = $this->query()->first();
        $this->assertEquals($this->fixtures['users'][0], $actual);
    }

    public function testFirstForEmpty()
    {
        $actual = $this->query()->eq('id', -1)->first();
        $this->assertNull($actual);
    }

    public function testLast()
    {
        $actual = $this->query()->last();
        $this->assertEquals($this->fixtures['users'][count($this->fixtures['users']) - 1], $actual);
    }

    public function testLastForEmpty()
    {
        $actual = $this->query()->eq('id', -1)->last();
        $this->assertNull($actual);
    }

    public function testExists()
    {
        $this->assertTrue($this->query()->eq('id', 1)->exists());
    }

    public function testNotExists()
    {
        $this->assertFalse($this->query()->eq('id', -1)->exists());
    }

    public function testMap()
    {
        $actual = $this->query()->map(function ($row) {
            return $row;
        });
        $this->assertEquals($this->fixtures['users'], $actual);
    }

    public function testMapWithEmpty()
    {
        $actual = $this->query()
            ->eq('id', -1)
            ->map(function ($row) {
                return $row;
            });
        $this->assertEquals(array(), $actual);
    }

    public function providerTestIn()
    {
        return [
            [[1], 1],
            [[1, 2], 2],
            [[4, 5], 1],
            [[-1], 0]
        ];
    }

    /**
     * @param $args
     * @param $expected
     * @dataProvider providerTestIn
     */
    public function testIn($args, $expected)
    {
        $actual = $this->query()->in('id', $args)->count();
        $this->assertEquals($expected, $actual);
    }

    public function providerTestNotIn()
    {
        return [
            [[1], 3],
            [[1, 2], 2],
            [[4, 5], 3],
            [[-1], 4]
        ];
    }

    /**
     * @param $args
     * @param $expected
     * @dataProvider providerTestNotIn
     */
    public function testNotIn($args, $expected)
    {
        $actual = $this->query()->notIn('id', $args)->count();
        $this->assertEquals($expected, $actual);
    }

    public function providerTestEq()
    {
        return [
            [1, ['created_at', '2016-10-10'], true],
            [2, ['created_at', '2016-10-10'], false]
        ];
    }

    /**
     * @param $id
     * @param $args
     * @param $expected
     * @dataProvider providerTestEq
     */
    public function testEq($id, $args, $expected)
    {
        $actual = $this->query()->eq('id', $id)->eq(...$args)->exists();
        $this->assertEquals($expected, $actual);
    }

    public function testAndOr()
    {
        $this->assertEquals(2, $this->query()
            ->eq('profile.age', 18)
            ->and(function (Where $where) {
                $where->eq('id', 1)->or(function (Where $where) {
                    $where->eq('id', 2) ;
                });
            })
            ->count());
        $this->assertEquals(1, $this->query()
            ->and(function (Where $where) {
                $where->eq('id', -1);
            })
            ->or(function (Where $where) {
                $where->eq('id', 1);
            })
            ->count());
    }

    public function testOrForEmpty()
    {
        $this->assertEquals(4, $this->query()
            ->or(function (Where $where) {
            })
            ->or(function (Where $where) {
            })
            ->count());
        $this->assertEquals(0, $this->query()
            ->or(function (Where $where) {
                $where->eq('a', -1);
            })
            ->or(function (Where $where) {
            })
            ->count());
        $this->assertEquals(0, $this->query()
            ->eq('a', -1)
            ->or(function (Where $where) {
            })
            ->count());
    }

    public function testOrEq()
    {
        $this->assertTrue(
            $this->query()
            ->eq('id', 1)
            ->or(function (Where $where) {
                $where->eq('profile.name', 'unknown2');
            })
            ->exists()
        );

        $this->assertFalse(
            $this->query()
                ->or(function (Where $where) {
                    $where->eq('id', -1);
                })
                ->or(function (Where $where) {
                    $where->eq('id', -2);
                })
                ->exists()
        );
    }

    public function providerTestGt()
    {
        return [
            [['created_at', '0000-01-01'], true],
            [['created_at', '9999-01-01'], false]
        ];
    }

    /**
     * @param $args
     * @param $expected
     * @dataProvider providerTestGt
     */
    public function testGt($args, $expected)
    {
        $actual = $this->query()->gt(...$args)->exists();
        $this->assertEquals($expected, $actual);
    }

    public function providerTestOrGt()
    {
        return [
            [2, ['created_at', '9999-01-01'], true],
            [9999, ['created_at', '9999-01-01'], false],
        ];
    }

    /**
     * @param $id
     * @param $args
     * @param $expected
     * @dataProvider providerTestOrGt
     */
    public function testOrGt($id, $args, $expected)
    {
        $actual = $this->query()->eq('id', $id)->or(function (Where $where) use ($args) {
            $where->gt(...$args);
        })->exists();
        $this->assertEquals($expected, $actual);
    }

    public function providerTestGte()
    {
        return [
            [['created_at', '2016-10-10'], true],
            [['created_at', '2016-10-14'], false],
        ];
    }

    /**
     * @param $args
     * @param $expected
     * @dataProvider providerTestGte
     */
    public function testGte($args, $expected)
    {
        $actual = $this->query()->gte(...$args)->exists();
        $this->assertEquals($expected, $actual);
    }

    public function providerTestOrGte()
    {
        return [
            [3, ['created_at', '2016-10-14'], true],
            [-2, ['created_at', '2016-10-10'], true],
            [-2, ['created_at', '2016-10-14'], false],
        ];
    }

    /**
     * @param $id
     * @param $args
     * @param $expected
     * @dataProvider providerTestOrGte
     */
    public function testOrGte($id, $args, $expected)
    {
        $actual = $this->query()->eq('id', $id)->or(function (Where $where) use ($args) {
            $where->gte(...$args);
        })->exists();
        $this->assertEquals($expected, $actual);
    }

    public function providerTestLt()
    {
        return [
            [['created_at', '0000-01-01'], false],
            [['created_at', '9999-01-01'], true]
        ];
    }

    /**
     * @param $args
     * @param $expected
     * @dataProvider providerTestLt
     */
    public function testLt($args, $expected)
    {
        $actual = $this->query()->lt(...$args)->exists();
        $this->assertEquals($expected, $actual);
    }

    public function providerTestOrLt()
    {
        return [
            [1, ['created_at', '0000-01-01'], true],
            [-1, ['created_at', '0000-01-01'], false],
            [9999, ['created_at', '9999-01-01'], true],
        ];
    }

    /**
     * @param $id
     * @param $args
     * @param $expected
     * @dataProvider providerTestOrLt
     */
    public function testOrLt($id, $args, $expected)
    {
        $actual = $this->query()->eq('id', $id)->or(function (Where $where) use ($args) {
            $where->lt(...$args);
        })->exists();
        $this->assertEquals($expected, $actual);
    }

    public function providerTestLte()
    {
        return [
            [['created_at', '2016-10-09'], false],
            [['created_at', '2016-10-10'], true],
            [['created_at', '2016-10-14'], true],
        ];
    }

    /**
     * @dataProvider providerTestLte
     * @param $args
     * @param $expected
     */
    public function testLte($args, $expected)
    {
        $actual = $this->query()->lte(...$args)->exists();
        $this->assertEquals($expected, $actual);
    }

    public function providerTestOrLte()
    {
        return [
            [3, ['created_at', '2016-10-14'], true],
            [-2, ['created_at', '2016-10-10'], true],
            [-2, ['created_at', '2016-10-09'], false]
        ];
    }

    /**
     * @dataProvider providerTestOrLte
     * @param $id
     * @param $args
     * @param $expected
     */
    public function testOrLte($id, $args, $expected)
    {
        $bool = $this->query()->eq('id', $id)->or(function (Where $where) use ($args) {
            $where->lte(...$args);
        })->exists();
        $this->assertEquals($expected, $bool);
    }

    public function providerTestLike()
    {
        return [
            ['u%', 4],
            ['%1', 1],
            ['unknown1', 1],
            ['%u', 0]
        ];
    }

    /**
     * @param $text
     * @param $expected
     * @dataProvider providerTestLike
     */
    public function testLike($text, $expected)
    {
        $this->assertEquals($expected, $this->countByProfileNameOfLike($text));
    }

    public function testNotLike()
    {
        $len = $this->query()
            ->notLike('profile.name', 'u%')
            ->count();
        $this->assertEquals(0, $len);
    }

    public function providerTestNull()
    {
        return [
            [['profile.sex'], 2],
            [['profile.sex', true], 1],
            [['profile.sex', false], 2],
        ];
    }

    /**
     * @param $args
     * @param $expected
     * @dataProvider providerTestNull
     */
    public function testNull($args, $expected)
    {
        $actual = $this->query()->null(...$args)->count();
        $this->assertEquals($expected, $actual);
    }

    public function providerTestNotNull()
    {
        return [
            ['profile.sex', 2],
            ['profile.friend', 2],
        ];
    }

    /**
     * @param $arg
     * @param $expected
     * @dataProvider providerTestNotNull
     */
    public function testNotNull($arg, $expected)
    {
        $actual = $this->query()->notNull($arg)->count();
        $this->assertEquals($expected, $actual);
    }

    public function testReset()
    {
        $len1 = $this->query()->eq('id', 1)->count();
        $len2 = $this->query()->reset()->count();
        $this->assertNotEquals($len1, $len2);
    }

    public function testUndefinedOperator()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->query()->dummy(1, 2);
    }

    private function query($fixture = 'users')
    {
        return new ArrayQuery($this->fixtures[$fixture]);
    }

    private function countByProfileNameOfLike($text)
    {
        return $this->query()->like('profile.name', $text)->count();
    }
}
