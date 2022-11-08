<?php

namespace Rikudou\PhpScad\Value;

final class IntValue extends NumericValue
{
    public function __construct(int $value)
    {
        parent::__construct($value);
    }

    public function getScadRepresentation(): string
    {
        return (string) $this->getValue();
    }
}
