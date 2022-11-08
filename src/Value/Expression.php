<?php

namespace Rikudou\PhpScad\Value;

final class Expression extends Reference
{
    public function __construct(
        private readonly string $expression,
    ) {
    }

    public function getScadRepresentation(): string
    {
        return "({$this->expression})";
    }
}
