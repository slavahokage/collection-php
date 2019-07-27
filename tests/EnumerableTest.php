<?php

require_once('../vendor/autoload.php');

use App\Enumerable;

class EnumerableTest extends \PHPUnit\Framework\TestCase
{
    public function testWhereStatements()
    {
        $elements = [
            ['key' => 'value', 'year' => 1932],
            ['key' => '', 'year' => 1100],
            ['key' => 'value', 'year' => 32]
        ];

        $enumerable = Enumerable::wrap($elements);
        $newEnumerable = $enumerable->where('key', 'value')->where('year', 1932);

        $this->assertEquals([['key' => 'value', 'year' => 1932]], $newEnumerable->all());
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