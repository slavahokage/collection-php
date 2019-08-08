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
            foreach ($elements as $key => $value) {
                if (!array_key_exists($keySought, $value) || !in_array($valueSought, $value)) {
                    unset($elements[$key]);
                }
            }

            return array_values($elements);
        };
    }

    private function callStatements()
    {
        while (!empty($this->statements)) {
            $statement = array_pop($this->statements);
            $this->elements = $statement($this->elements);
        }
    }
}
