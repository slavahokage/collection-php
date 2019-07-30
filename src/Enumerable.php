<?php

namespace App;

class Enumerable
{
    private $elements = [];

    private $functionsCall = [];

    private function __construct($elements)
    {
        $this->elements = $elements;
    }

    public function setFunctionsCall(array $functionsCall)
    {
        $this->functionsCall = $functionsCall;
    }

    public static function wrap(array $elements): Enumerable
    {
        return new Enumerable($elements);
    }

    public function where($keySought, $valueSought)
    {
        $this->functionsCall[] = function () use ($valueSought, $keySought) {
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
        foreach ($this->functionsCall as $func) {
            $func();
        }

        return $this->elements;
    }
}
