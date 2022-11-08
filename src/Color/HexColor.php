<?php

namespace Rikudou\PhpScad\Color;

use Rikudou\PhpScad\Implementation\ValueConverter;
use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;
use Rikudou\PhpScad\Value\StringValue;

final class HexColor extends AbstractColor
{
    use ValueConverter;

    public function __construct(
        private readonly StringValue|Reference|string $hex,
        private readonly NumericValue|Reference|float $alpha = 1.0,
    ) {
    }

    public function getScadRepresentation(): string
    {
        return "c = {$this->convertToValue($this->hex)}, alpha = {$this->alpha}";
    }
}
