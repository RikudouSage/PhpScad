<?php

namespace Rikudou\PhpScad\Coordinate;

use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;

interface Coordinate
{
    public function getX(): Reference|NumericValue|float;

    public function getY(): Reference|NumericValue|float;

    public function getZ(): Reference|NumericValue|float;

    public function add(Coordinate $coordinate): self;
}
