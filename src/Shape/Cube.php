<?php

namespace Rikudou\PhpScad\Shape;

use JetBrains\PhpStorm\Immutable;
use Rikudou\PhpScad\Implementation\ConditionalRenderable;
use Rikudou\PhpScad\Implementation\RenderableImplementation;
use Rikudou\PhpScad\Implementation\ValueConverter;
use Rikudou\PhpScad\Implementation\Wither;
use Rikudou\PhpScad\Primitive\HasWrappers;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Value\BoolValue;
use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;

#[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
final class Cube implements Renderable, HasWrappers
{
    use RenderableImplementation;
    use ConditionalRenderable;
    use Wither;
    use ValueConverter;

    private NumericValue|Reference $width;

    private NumericValue|Reference $depth;

    private NumericValue|Reference $height;

    private BoolValue|Reference $center;

    /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
    public function __construct(
        NumericValue|Reference|float $width = 0,
        NumericValue|Reference|float $depth = 0,
        NumericValue|Reference|float $height = 0,
        BoolValue|Reference|bool $center = false,
    ) {
        $this->width = $this->convertToValue($width);
        $this->depth = $this->convertToValue($depth);
        $this->height = $this->convertToValue($height);
        $this->center = $this->convertToValue($center);
    }

    public function getWidth(): NumericValue|Reference
    {
        return $this->width;
    }

    public function getDepth(): NumericValue|Reference
    {
        return $this->depth;
    }

    public function getHeight(): NumericValue|Reference
    {
        return $this->height;
    }

    public function isCentered(): BoolValue|Reference
    {
        return $this->center;
    }

    public function withWidth(NumericValue|Reference|float $width): self
    {
        return $this->with('width', $this->convertToValue($width));
    }

    public function withDepth(NumericValue|Reference|float $depth): self
    {
        return $this->with('depth', $this->convertToValue($depth));
    }

    public function withHeight(NumericValue|Reference|float $height): self
    {
        return $this->with('height', $this->convertToValue($height));
    }

    public function withCentered(BoolValue|Reference|bool $center): self
    {
        return $this->with('center', $this->convertToValue($center));
    }

    protected function isRenderable(): bool
    {
        return (!$this->getDepth()->hasLiteralValue() || ($this->getDepth()->getValue() > 0))
            || (!$this->getWidth()->hasLiteralValue() || ($this->getWidth()->getValue() > 0))
            || (!$this->getHeight()->hasLiteralValue() || ($this->getHeight()->getValue() > 0))
        ;
    }

    protected function doRender(): string
    {
        return sprintf(
            'cube([%s, %s, %s], center = %s);',
            $this->width,
            $this->depth,
            $this->height,
            $this->center,
        );
    }
}
