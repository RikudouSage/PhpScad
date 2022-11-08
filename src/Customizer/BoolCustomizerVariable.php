<?php

namespace Rikudou\PhpScad\Customizer;

final class BoolCustomizerVariable extends AbstractCustomizerVariable
{
    public function __construct(string $name, bool $value, ?string $description = null)
    {
        parent::__construct($name, $value, $description);
    }

    public function getValue(): bool
    {
        return parent::getValue();
    }
}
