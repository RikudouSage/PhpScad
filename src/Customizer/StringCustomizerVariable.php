<?php

namespace Rikudou\PhpScad\Customizer;

final class StringCustomizerVariable extends AbstractCustomizerVariable
{
    public function __construct(string $name, string $value, ?string $description = null)
    {
        parent::__construct($name, $value, $description);
    }

    public function getValue(): string
    {
        return parent::getValue();
    }
}
