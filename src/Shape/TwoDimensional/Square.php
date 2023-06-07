<?php

namespace Rikudou\PhpScad\Shape\TwoDimensional;

use Rikudou\PhpScad\Implementation\ConditionalRenderable;
use Rikudou\PhpScad\Implementation\TwoDimensionalShapeImplementation;
use Rikudou\PhpScad\Implementation\ValueConverter;
use Rikudou\PhpScad\Primitive\TwoDimensionalShape;
use Rikudou\PhpScad\Value\BoolValue;
use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;

final class Square implements TwoDimensionalShape
{
    use ConditionalRenderable;
    use ValueConverter;
    use TwoDimensionalShapeImplementation;

    private NumericValue|Reference $width;

    private NumericValue|Reference $height;

    private BoolValue|Reference $center;

    /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
    public function __construct(
        NumericValue|Reference|float $width = 0,
        NumericValue|Reference|float $height = 0,
        BoolValue|Reference|bool $center = false,
    ) {
        $this->width = $this->convertToValue($width);
        $this->height = $this->convertToValue($height);
        $this->center = $this->convertToValue($center);
    }

    protected function doRender(): string
    {
        return sprintf(
            'square(size = [%s, %s], center = %s);',
            $this->width,
            $this->height,
            $this->center,
        );
    }

    protected function isRenderable(): bool
    {
        return ($this->width instanceof Reference || $this->width->getValue() > 0)
            && ($this->height instanceof Reference || $this->height->getValue() > 0);
    }
}
