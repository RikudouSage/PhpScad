<?php

namespace Rikudou\PhpScad\Customizer;

final class NullCustomizerVariable extends AbstractCustomizerVariable
{
    public function __construct(string $name, ?string $description = null)
    {
        parent::__construct($name, null, $description);
    }
}
