<?php

namespace App;

class Enumerable
{
    private $elements = [];

    private $functionsCall = [];

    private function __construct($elements, $functionsCall)
    {
        $this->elements = $elements;
        $this->functionsCall = $functionsCall;
    }

    public static function wrap(array $elements): Enumerable
    {
        return new Enumerable($elements, []);
    }

    public function where($keySought, $valueSought)
    {
        $this->functionsCall[] = function ($elements) use ($valueSought, $keySought) {
            foreach ($elements as $key => $value) {
                if (!array_key_exists($keySought, $value) || !in_array($valueSought, $value)) {
                    unset($elements[$key]);
                }
            }

            return $elements;
        };

        return new Enumerable($this->elements, $this->functionsCall);
    }

    public function all()
    {
        foreach ($this->functionsCall as $func) {
            $this->elements = $func($this->elements);
        }

        return $this->elements;
    }
}
