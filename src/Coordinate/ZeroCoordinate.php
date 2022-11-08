<?php

namespace Rikudou\PhpScad\Coordinate;

final class ZeroCoordinate extends AbstractCoordinate
{
    public function getX(): float
    {
        return 0;
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
