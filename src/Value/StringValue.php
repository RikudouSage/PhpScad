<?php

namespace Rikudou\PhpScad\Value;

final class StringValue extends Literal
{
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    public function getValue(): string
    {
        return parent::getValue();
    }

    public function getScadRepresentation(): string
    {
        return "\"{$this->getValue()}\"";
    }
}
