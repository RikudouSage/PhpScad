<?php

namespace Rikudou\PhpScad\Value;

abstract class Reference implements Value
{
    public function __toString(): string
    {
        return $this->getScadRepresentation();
    }

    public function getValue(): mixed
    {
        return null;
    }

    public function hasLiteralValue(): bool
    {
        return false;
    }
}
