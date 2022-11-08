<?php

namespace Rikudou\PhpScad\Value;

final class Variable extends Reference
{
    public function __construct(
        private readonly string $variableName,
    ) {
    }

    public function getScadRepresentation(): string
    {
        $name = $this->variableName;
        if (!str_starts_with($name, '$')) {
            $name = "\${$name}";
        }

        return $name;
    }
}
