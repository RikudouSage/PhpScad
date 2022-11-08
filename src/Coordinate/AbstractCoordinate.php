<?php

namespace Rikudou\PhpScad\Coordinate;

use Rikudou\PhpScad\Implementation\ValueConverter;
use Rikudou\PhpScad\Value\Expression;
use Rikudou\PhpScad\Value\FloatValue;
use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;

abstract class AbstractCoordinate implements Coordinate
{
    use ValueConverter;

    public function add(Coordinate $coordinate): Coordinate
    {
        $currentX = $this->convertToValue($this->getX());
        $currentY = $this->convertToValue($this->getY());
        $currentZ = $this->convertToValue($this->getZ());

        $newX = $this->convertToValue($coordinate->getX());
        $newY = $this->convertToValue($coordinate->getY());
        $newZ = $this->convertToValue($coordinate->getZ());

        return new XYZ(
            $this->addValues($currentX, $newX),
            $this->addValues($currentY, $newY),
            $this->addValues($currentZ, $newZ),
        );
    }

    private function addValues(NumericValue|Reference $value1, NumericValue|Reference $value2): NumericValue|Reference
    {
        if ($value1->hasLiteralValue() && $value2->hasLiteralValue()) {
            return new FloatValue($value1->getValue() + $value2->getValue());
        }

        return new Expression("{$value1} + {$value2}");
    }
}
