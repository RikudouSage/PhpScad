<?php

namespace Rikudou\PhpScad\Coordinate;

use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;

final class X extends AbstractCoordinate
{
    public function __construct(
        private readonly Reference|NumericValue|float $x,
    ) {
    }

    public function getX(): Reference|NumericValue|float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return 0;
    }

    public function getZ(): float
    {
        return 0;
    }
}
