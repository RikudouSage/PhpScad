<?php

namespace Rikudou\PhpScad\Color;

use Stringable;

interface Color extends Stringable
{
    public function getScadRepresentation(): string;
}
