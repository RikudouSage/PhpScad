<?php

namespace Rikudou\PhpScad\Implementation;

use Rikudou\PhpScad\FacetsConfiguration\FacetsConfiguration;
use Rikudou\PhpScad\Primitive\TwoDimensionalShape;
use Rikudou\PhpScad\Transformation\LinearExtrude;
use Rikudou\PhpScad\Value\BoolValue;
use Rikudou\PhpScad\Value\IntValue;
use Rikudou\PhpScad\Value\NullValue;
use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;

trait TwoDimensionalShapeImplementation
{
    public function linearExtrude(
        NumericValue|Reference|float $height,
        BoolValue|Reference|bool $center = false,
        IntValue|Reference|NullValue|int|null $convexity = null,
        NumericValue|Reference|float $twist = 0,
        IntValue|Reference|NullValue|int|null $slices = null,
        NumericValue|Reference|float $scale = 1,
        ?FacetsConfiguration $facetsConfiguration = null,
    ): LinearExtrude {
        assert($this instanceof TwoDimensionalShape);

        return new LinearExtrude($height, $center, $convexity, $twist, $slices, $scale, $facetsConfiguration, $this);
    }
}
