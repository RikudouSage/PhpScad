<?php

namespace Rikudou\PhpScad\Font;

use Stringable;

interface FontReference extends Stringable
{
    public function getName(): string;
}
