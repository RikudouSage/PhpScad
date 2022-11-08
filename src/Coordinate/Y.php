<?php

namespace Rikudou\PhpScad\Coordinate;

use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;

final class Y extends AbstractCoordinate
{
    public function __construct(
        private readonly Reference|NumericValue|float $y,
    ) {
    }

    public function getX(): float
    {
        return 0;
    }

    public function getY(): Reference|NumericValue|float
    {
        return $this->y;
    }

    public function getZ(): float
    {
        return 0;
    }
}
