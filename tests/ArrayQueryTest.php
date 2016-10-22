<?php

namespace Rmtram\ArrayQuery\Tests;

use Rmtram\ArrayQuery\ArrayQuery;

class ArrayQueryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var array
     */
    private $fixtures = array();

    public function setUp()
    {
        $this->fixtures['users'] = require __DIR__ . '/fixtures/users.php';
    }

    public function testAll()
    {
        $this->assertEquals($this->fixtures['users'], $this->query()->all());
    }

    public function testAllWithOrder()
    {
        $actual = $this->query()->order('id', 'desc')->all();
        $this->assertEquals(3, $actual[0]['id']);
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

    public function testFirstWithOrder()
    {
        $actual = $this->query()->order('id', 'desc')->first();
        $this->assertEquals(3, $actual['id']);
    }


    public function testFirstToEmptyResult()
    {
        $actual = $this->query()->eq('id', -1)->first();
        $this->assertEquals(null, $actual);
    }

    public function testExists()
    {
        $this->assertTrue($this->query()->eq('id', 1)->exists());
    }

    public function testNotExists()
    {
        $this->assertFalse($this->query()->eq('id', -1)->exists());
    }

    public function testEach()
    {
        $actual = array();
        $this->query()->each(function($row) use(&$actual) {
            $actual[] = $row;
        });
        $this->assertEquals($this->fixtures['users'], $actual);
    }

    public function testEachWithOrder()
    {
        $actual = array();
        $this->query()
            ->order('id', 'desc')
            ->each(function($row) use(&$actual) {
                $actual[] = $row;
            });
        $this->assertEquals(3, $actual[0]['id']);
    }

    public function testEachWithEmpty()
    {
        $actual = array();
        $this->query()
            ->eq('id', -1)
            ->each(function($row) use(&$actual) {
                $actual[] = $row;
            });
        $this->assertEquals(array(), $actual);
    }

    public function testMap()
    {
        $actual = $this->query()->map(function($row) {
            return $row;
        });
        $this->assertEquals($this->fixtures['users'], $actual);
    }

    public function testMapWithOrder()
    {
        $actual = $this->query()
            ->order('id', 'desc')
            ->map(function($row)  {
                return $row;
            });
        $this->assertEquals(3, $actual[0]['id']);
    }

    public function testMapWithEmpty()
    {
        $actual = $this->query()
            ->eq('id', -1)
            ->map(function($row) {
                return $row;
            });
        $this->assertEquals(array(), $actual);
    }

    public function testEq()
    {
        $this->assertTrue(
            $this->query()
                ->eq('id', 1)
                ->eq('created_at', '2016-10-10')
                ->exists()
        );

        $this->assertFalse(
            $this->query()
                ->eq('id', 2)
                ->eq('created_at', '2016-10-10')
                ->exists()
        );
    }

    public function testOrEq()
    {
        $this->assertTrue(
          $this->query()
            ->eq('id', 1)
            ->orEq('profile.name', 'unknown2')
            ->exists()
        );

        $this->assertFalse(
            $this->query()
                ->orEq('id', -1)
                ->orEq('id', -2)
                ->exists()
        );
    }

    public function testGt()
    {
        $this->assertTrue(
          $this->query()
              ->gt('created_at', '0000-01-01')
              ->exists()
        );

        $this->assertFalse(
            $this->query()
                ->gt('created_at', '9999-01-01')
                ->exists()
        );
    }

    public function testOrGt()
    {
        $this->assertTrue(
            $this->query()
                ->eq('id', 2)
                ->orGt('created_at', '9999-01-01')
                ->exists()
        );

        $this->assertFalse(
            $this->query()
                ->eq('id', 9999)
                ->orGt('created_at', '9999-01-01')
                ->exists()
        );
    }


    public function testLike()
    {
        $len = $this->query()
            ->like('profile.name', 'unknown%')
            ->count();
        $this->assertEquals(3, $len);
    }

    public function testNotLike()
    {
        $len = $this->query()
            ->notLike('profile.name', 'unknown%')
            ->count();
        $this->assertEquals(0, $len);
    }

    public function testReset()
    {
        $len1 = $this->query()->eq('id', 1)->count();
        $len2 = $this->query()->reset()->count();
        $this->assertNotEquals($len1, $len2);
    }

    public function testUndefinedOperator()
    {
        $expected = 'BadMethodCallException';
        $exception = null;
        try {
            $this->query()->dummy(1, 2);
        } catch (\Exception $exception) {}

        $this->assertInstanceOf($expected, $exception);
    }

    private function query($fixture = 'users')
    {
        return new ArrayQuery($this->fixtures[$fixture]);
    }

}