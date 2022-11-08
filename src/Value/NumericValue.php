<?php

namespace Rikudou\PhpScad\Value;

abstract class NumericValue extends Literal
{
    public function __construct(int|float $value)
    {
        parent::__construct($value);
    }

    public function getValue(): int|float
    {
        return parent::getValue();
    }
}
