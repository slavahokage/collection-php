<?php

namespace App;

class Enumerable
{
    private $elements = [];

    private $stack = [];

    private function __construct($elements)
    {
        $this->elements = $elements;
    }

    public static function wrap(array $elements): Enumerable
    {
        return new Enumerable($elements);
    }

    public function where($keySought, $valueSought)
    {
        $this->stack[] = function () use ($valueSought, $keySought) {
            foreach ($this->elements as $key => $value) {
                if (!array_key_exists($keySought, $value) || !in_array($valueSought, $value)) {
                    unset($this->elements[$key]);
                }
            }
        };

        return $this;
    }

    public function all()
    {
        foreach ($this->stack as $f) {
            $f();
        }

        return $this->elements;
    }
}
