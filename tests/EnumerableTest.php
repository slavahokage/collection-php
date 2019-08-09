<?php

namespace App\Tests;

use App\Enumerable;

class EnumerableTest extends \PHPUnit\Framework\TestCase
{
    public function testWhereStatements()
    {
        $elements = [
            ['k' => 'value', 'year' => 1932],
            ['k' => '', 'year' => 1100],
            ['k' => 'value', 'year' => 32],
            ['k' => 'value', 'year' => 32, 'testKey' => 'testValue']
        ];

        $enumerable = Enumerable::wrap($elements);

        $f = function ($elements) {

            foreach ($elements as $key => $value) {
                if (!array_key_exists('testKey', $value) || !in_array('testValue', $value)) {
                    unset($elements[$key]);
                }
            }

            return $elements;
        };

        $newEnumerable = $enumerable->where('k', 'value')
            ->where('year', 32)
            ->where($f);

        $this->assertEquals([['k' => 'value', 'year' => 32, 'testKey' => 'testValue']], $newEnumerable->all());
    }

    public function testEnumerableType()
    {
        $elements = [
            ['key' => 'value', 'year' => 1932],
            ['key' => '', 'year' => 1100],
            ['key' => 'value', 'year' => 32]
        ];

        $enumerable = Enumerable::wrap($elements);

        $this->assertTrue($enumerable instanceof Enumerable);
    }

    public function testAllResults()
    {
        $elements = [
            ['key' => 'value', 'year' => 1932],
            ['key' => '', 'year' => 1100],
            ['key' => 'value', 'year' => 32]
        ];

        $enumerable = Enumerable::wrap($elements);

        $this->assertEquals($elements, $enumerable->all());
    }
}