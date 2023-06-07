<?php

namespace Rikudou\PhpScad\Value;

final class PiValue extends Reference
{
    public function getScadRepresentation(): string
    {
        return 'PI';
    }
}
