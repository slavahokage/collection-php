<?php

namespace App;

class Enumerable
{
    private $elements = [];

    private $statements;

    private function __construct($elements, $statements)
    {
        $this->elements = $elements;
        if ($statements === null) {
            $this->statements = new \SplStack();
        } else {
            $this->statements = $statements;
        }
    }

    public static function wrap(array $elements): Enumerable
    {
        return new Enumerable($elements, null);
    }

    public function where($keySought, $valueSought)
    {
        $this->statements->push($this->whereStatement($valueSought, $keySought));

        return new Enumerable($this->elements, $this->statements);
    }

    public function all()
    {
        $this->callStatements();

        return $this->elements;
    }

    private function whereStatement($valueSought, $keySought)
    {
        return function ($elements) use ($valueSought, $keySought) {
            foreach ($elements as $key => $value) {
                if (!array_key_exists($keySought, $value) || !in_array($valueSought, $value)) {
                    unset($elements[$key]);
                }
            }

            return $elements;
        };
    }

    private function callStatements()
    {
        while (!$this->statements->isEmpty()) {
            $statement = $this->statements->pop();
            $this->elements = $statement($this->elements);
        }
    }
}
