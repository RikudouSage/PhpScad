<?php

namespace Rikudou\PhpScad\Module;

use Rikudou\PhpScad\Primitive\Module;

final class CustomizerEndModule implements Module
{
    public function getName(): string
    {
        return '__Customizer_End';
    }

    public function getDefinition(): string
    {
        return "module {$this->getName()}() {}";
    }
}
