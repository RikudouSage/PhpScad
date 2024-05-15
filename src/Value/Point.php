<?php

namespace Rikudou\PhpScad\Value;

use Rikudou\PhpScad\Implementation\ValueConverter;

final class Point implements Value
{
    use ValueConverter;

    private NumericValue|Reference $x;

    private NumericValue|Reference $y;

    private NumericValue|Reference $z;

    public function __construct(
        float|NumericValue|Reference $x,
        float|NumericValue|Reference $y,
        float|NumericValue|Reference $z,
    ) {
        $this->x = $this->convertToValue($x);
        $this->y = $this->convertToValue($y);
        $this->z = $this->convertToValue($z);
    }

    public function __toString(): string
    {
        return $this->getScadRepresentation();
    }

    public function getValue(): array
    {
        return [$this->x, $this->y, $this->z];
    }

    public function getScadRepresentation(): string
    {
        $value = $this->getValue();

        return "[{$value[0]}, {$value[1]}, {$value[2]}]";
    }

    public function hasLiteralValue(): bool
    {
        return $this->x->hasLiteralValue()
            && $this->y->hasLiteralValue()
            && $this->z->hasLiteralValue()
        ;
    }

    public function getX(): Reference|NumericValue
    {
        return $this->x;
    }

    public function getY(): Reference|NumericValue
    {
        return $this->y;
    }

    public function getZ(): Reference|NumericValue
    {
        return $this->z;
    }
}
