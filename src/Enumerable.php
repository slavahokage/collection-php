<?php

namespace App;

class Enumerable
{
    private $elements = [];

    private $statements = [];

    private function __construct($elements, $statements)
    {
        $this->elements = $elements;
        $this->statements = $statements;
    }

    public static function wrap(array $elements)
    {
        return new Enumerable($elements, []);
    }

    public function where($keySought, $valueSought = null)
    {
        $newEnumerable = new Enumerable($this->elements, $this->statements);

        $newEnumerable->statements[] = $this->whereStatement($valueSought, $keySought);

        return $newEnumerable;
    }

    public function all()
    {
        $newEnumerable = new Enumerable($this->elements, $this->statements);

        $newEnumerable->callStatements();

        return $newEnumerable->elements;
    }

    private function whereStatement($valueSought, $keySought)
    {
        if (is_callable($keySought)) {
            return $keySought;
        }

        return function ($elements) use ($valueSought, $keySought) {
            $newElements = [];

            foreach ($elements as $key => $value) {
                if (array_key_exists($keySought, $value) || in_array($valueSought, $value)) {
                    $newElements[] = $elements[$key];
                }
            }

            return $newElements;
        };
    }

    private function callStatements()
    {
        $this->takeOutStatements($this->statements, $this->elements);
    }

    private function takeOutStatements(&$statements, &$elements)
    {
        if (empty($statements)) {
            return;
        }

        $statement = array_pop($statements);
        $elements = $statement($elements);

        $this->takeOutStatements($statements, $elements);
    }
}
