<?php

namespace Rikudou\PhpScad\Implementation;

use Error;
use Rikudou\PhpScad\Color\Color;
use Rikudou\PhpScad\Combination\Difference;
use Rikudou\PhpScad\Combination\Intersection;
use Rikudou\PhpScad\Combination\Union;
use Rikudou\PhpScad\Coordinate\Coordinate;
use Rikudou\PhpScad\Coordinate\X;
use Rikudou\PhpScad\Coordinate\Y;
use Rikudou\PhpScad\Coordinate\Z;
use Rikudou\PhpScad\Coordinate\ZeroCoordinate;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Transformation\ColorChange;
use Rikudou\PhpScad\Transformation\Translate;
use Rikudou\PhpScad\Value\Expression;
use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;
use Rikudou\PhpScad\Wrapper\WrapperConfiguration;
use Rikudou\PhpScad\Wrapper\WrapperValuePlaceholder;

trait RenderableImplementation
{
    use Wither;
    use ValueConverter;

    private Coordinate $position;

    private ?Color $color = null;

    public function getPosition(): Coordinate
    {
        try {
            return $this->position;
        } catch (Error) {
            return new ZeroCoordinate();
        }
    }

    public function withPosition(Coordinate $position): self
    {
        return $this->with('position', $position);
    }

    public function movedBy(Coordinate $coordinate): self
    {
        return $this->withPosition(
            $this->getPosition()->add($coordinate),
        );
    }

    public function movedRight(NumericValue|Reference|float $millimeters): self
    {
        return $this->movedBy(new X($this->convertToValue($millimeters)));
    }

    public function movedLeft(NumericValue|Reference|float $millimeters): self
    {
        return $this->movedBy(new X(new Expression("-{$millimeters}")));
    }

    public function movedUp(NumericValue|Reference|float $millimeters): self
    {
        return $this->movedBy(new Y($this->convertToValue($millimeters)));
    }

    public function movedDown(NumericValue|Reference|float $millimeters): self
    {
        return $this->movedBy(new Y(new Expression("-{$millimeters}")));
    }

    public function movedUpOnZ(NumericValue|Reference|float $millimeters): self
    {
        return $this->movedBy(new Z($this->convertToValue($millimeters)));
    }

    public function movedDownOnZ(NumericValue|Reference|float $millimeters): self
    {
        return $this->movedBy(new Z(new Expression("-{$millimeters}")));
    }

    public function joinedWith(Renderable ...$renderable): Union
    {
        return new Union($this, ...$renderable);
    }

    public function subtractedWith(Renderable ...$renderable): Difference
    {
        return new Difference($this, ...$renderable);
    }

    public function intersectedWith(Renderable ...$renderable): Intersection
    {
        return new Intersection($this, ...$renderable);
    }

    public function getWrappers(): iterable
    {
        yield new WrapperConfiguration(
            Translate::class,
            $this->getPosition(),
            new WrapperValuePlaceholder(),
        );
        yield new WrapperConfiguration(
            ColorChange::class,
            $this->getColor(),
            new WrapperValuePlaceholder(),
        );
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function withColor(?Color $color): self
    {
        return $this->with('color', $color);
    }
}
