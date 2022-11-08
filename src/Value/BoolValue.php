<?php

namespace Rikudou\PhpScad\Value;

final class BoolValue extends Literal
{
    public function __construct(bool $value)
    {
        parent::__construct($value);
    }

    public function getScadRepresentation(): string
    {
        return $this->getValue() ? 'true' : 'false';
    }
}
