<?php

namespace Rikudou\PhpScad\Shape;

use Rikudou\PhpScad\Implementation\AliasShape;
use Rikudou\PhpScad\Implementation\ValueConverter;
use Rikudou\PhpScad\Primitive\HasWrappers;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Value\Expression;
use Rikudou\PhpScad\Value\Face;
use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Point;
use Rikudou\PhpScad\Value\Reference;

final class Pyramid implements Renderable, HasWrappers
{
    use AliasShape;
    use ValueConverter;

    private NumericValue|Reference $width;

    private NumericValue|Reference $depth;

    private NumericValue|Reference $height;

    public function __construct(
        NumericValue|Reference|float $width,
        NumericValue|Reference|float $depth,
        NumericValue|Reference|float $height,
    ) {
        $this->width = $this->convertToValue($width);
        $this->depth = $this->convertToValue($depth);
        $this->height = $this->convertToValue($height);
    }

    protected function getAliasedShape(): Renderable
    {
        $topPoint = new Point(
            x: $this->width->hasLiteralValue()
                ? $this->width->getValue() / 2
                : new Expression("{$this->width} / 2"),
            y: $this->depth->hasLiteralValue()
                ? $this->depth->getValue() / 2
                : new Expression("{$this->depth} / 2"),
            z: $this->height,
        );

        $bottomLeft = new Point(0, 0, 0);
        $topLeft = new Point(0, $this->depth, 0);
        $topRight = new Point($this->width, $this->depth, 0);
        $bottomRight = new Point($this->width, 0, 0);

        return $this->getWrapped(new Polyhedron(
            faces: [
                new Face($topRight, $bottomRight, $topPoint),
                new Face($bottomRight, $bottomLeft, $topPoint),
                new Face($bottomLeft, $topLeft, $topPoint),
                new Face($topLeft, $topRight, $topPoint),
                new Face($topRight, $topLeft, $bottomLeft, $bottomRight),
            ],
        ));
    }
}
