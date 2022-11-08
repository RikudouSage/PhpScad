<?php

namespace Rikudou\PhpScad\Color;

use Rikudou\PhpScad\Implementation\ValueConverter;
use Rikudou\PhpScad\Value\Expression;
use Rikudou\PhpScad\Value\FloatValue;
use Rikudou\PhpScad\Value\IntValue;
use Rikudou\PhpScad\Value\Reference;

final class RGBColor extends AbstractColor
{
    use ValueConverter;

    public function __construct(
        private readonly IntValue|Reference|int $red,
        private readonly IntValue|Reference|int $green,
        private readonly IntValue|Reference|int $blue,
        private readonly FloatValue|Reference|float $alpha = 1.0,
    ) {
    }

    public function getScadRepresentation(): string
    {
        $red = $this->convertToValue($this->red);
        $green = $this->convertToValue($this->green);
        $blue = $this->convertToValue($this->blue);
        $alpha = $this->convertToValue($this->alpha);

        $red = $red->hasLiteralValue() ? $red->getValue() / 255 : new Expression("{$red} / 255");
        $green = $green->hasLiteralValue() ? $green->getValue() / 255 : new Expression("{$green} / 255");
        $blue = $blue->hasLiteralValue() ? $blue->getValue() / 255 : new Expression("{$blue} / 255");

        return "c = [{$red}, {$green}, {$blue}], alpha = {$alpha}";
    }
}
