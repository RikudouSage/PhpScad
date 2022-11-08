<?php

namespace Rikudou\PhpScad\Value;

abstract class Literal implements Value
{
    public function __construct(
        private readonly mixed $value,
    ) {
    }

    public function __toString(): string
    {
        return $this->getScadRepresentation();
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function hasLiteralValue(): bool
    {
        return true;
    }
}
