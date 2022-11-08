<?php

namespace Rikudou\PhpScad\Coordinate;

use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;

final class Z extends AbstractCoordinate
{
    public function __construct(
        private readonly Reference|NumericValue|float $z,
    ) {
    }

    public function getX(): float
    {
        return 0;
    }

    public function getY(): float
    {
        return 0;
    }

    public function getZ(): Reference|NumericValue|float
    {
        return $this->z;
    }
}
