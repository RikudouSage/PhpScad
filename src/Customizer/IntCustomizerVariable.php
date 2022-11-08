<?php

namespace Rikudou\PhpScad\Customizer;

final class IntCustomizerVariable extends AbstractNumericCustomizerVariable
{
    public function __construct(string $name, int $value, ?string $description = null)
    {
        parent::__construct($name, $value, $description);
    }

    public function getValue(): int
    {
        return parent::getValue();
    }
}
