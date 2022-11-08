<?php

namespace Rikudou\PhpScad\Customizer;

final class FloatCustomizerVariable extends AbstractNumericCustomizerVariable
{
    public function __construct(string $name, float $value, ?string $description = null)
    {
        parent::__construct($name, $value, $description);
    }

    public function getValue(): float
    {
        return parent::getValue();
    }
}
