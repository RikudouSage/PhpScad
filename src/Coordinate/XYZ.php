<?php

namespace Rikudou\PhpScad\Coordinate;

use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;

final class XYZ extends AbstractCoordinate
{
    public function __construct(
        private readonly Reference|NumericValue|float $x,
        private readonly Reference|NumericValue|float $y,
        private readonly Reference|NumericValue|float $z,
    ) {
    }

    public function getX(): Reference|NumericValue|float
    {
        return $this->x;
    }

    public function getY(): Reference|NumericValue|float
    {
        return $this->y;
    }

    public function getZ(): Reference|NumericValue|float
    {
        return $this->z;
    }
}
