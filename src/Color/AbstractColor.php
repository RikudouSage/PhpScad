<?php

namespace Rikudou\PhpScad\Color;

abstract class AbstractColor implements Color
{
    public function __toString(): string
    {
        return $this->getScadRepresentation();
    }
}
