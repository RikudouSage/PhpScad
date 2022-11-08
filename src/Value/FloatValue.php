<?php

namespace Rikudou\PhpScad\Value;

final class FloatValue extends NumericValue
{
    public function __construct(float $value)
    {
        parent::__construct($value);
    }

    public function getScadRepresentation(): string
    {
        return (string) $this->getValue();
    }
}
