<?php

namespace Rikudou\PhpScad\Customizer;

abstract class AbstractNumericCustomizerVariable extends AbstractCustomizerVariable
{
    public function __construct(
        string $name,
        float|int $value,
        ?string $description = null,
    ) {
        parent::__construct($name, $value, $description);
    }

    public function getValue(): int|float
    {
        return parent::getValue();
    }
}
