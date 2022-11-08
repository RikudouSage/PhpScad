<?php

namespace Rikudou\PhpScad\Value;

use Stringable;

interface Value extends Stringable
{
    public function getScadRepresentation(): string;

    public function getValue(): mixed;

    public function hasLiteralValue(): bool;
}
