<?php

namespace Rikudou\PhpScad\Transformation;

use Rikudou\PhpScad\Coordinate\Coordinate;
use Rikudou\PhpScad\Implementation\RenderableImplementation;
use Rikudou\PhpScad\Implementation\WrapperModuleDefinitions;
use Rikudou\PhpScad\Implementation\WrapperRenderableImplementation;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Primitive\WrapperRenderable;

final class Translate implements WrapperRenderable
{
    use RenderableImplementation;
    use WrapperModuleDefinitions;
    use WrapperRenderableImplementation;

    public function __construct(
        Coordinate $position,
        Renderable ...$renderable,
    ) {
        $this->renderables = $renderable;
        $this->position = $position;
    }

    public function getWrappers(): iterable
    {
        return [];
    }

    protected function doRender(): string
    {
        $x = $this->convertToValue($this->position->getX());
        $y = $this->convertToValue($this->position->getY());
        $z = $this->convertToValue($this->position->getZ());

        $usefulTranslate =
            !$x->hasLiteralValue()
            || $x->getValue() !== 0.0
            || !$y->hasLiteralValue()
            || $y->getValue() !== 0.0
            || !$z->hasLiteralValue()
            || $z->getValue() !== 0.0
        ;

        $result = '';
        if ($usefulTranslate) {
            $result .= "translate([{$x}, {$y}, {$z}]) {";
        }
        $result .= $this->renderUnwrappedRenderables();
        if ($usefulTranslate) {
            $result .= '}';
        }

        return $result;
    }
}
