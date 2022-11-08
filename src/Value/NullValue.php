<?php

namespace Rikudou\PhpScad\Value;

final class NullValue extends Literal
{
    public function __construct()
    {
        parent::__construct(null);
    }

    public function getScadRepresentation(): string
    {
        return 'undef';
    }
}
