<?php

namespace Rikudou\PhpScad\Transformation;

use Rikudou\PhpScad\Implementation\RenderableImplementation;
use Rikudou\PhpScad\Implementation\WrapperModuleDefinitions;
use Rikudou\PhpScad\Implementation\WrapperRenderableImplementation;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Primitive\WrapperRenderable;
use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;

final class Scale implements WrapperRenderable
{
    use RenderableImplementation;
    use WrapperModuleDefinitions;
    use WrapperRenderableImplementation;

    private NumericValue|Reference $scaleX;

    private NumericValue|Reference $scaleY;

    private NumericValue|Reference $scaleZ;

    public function __construct(
        NumericValue|Reference|float $scaleX = 1,
        NumericValue|Reference|float $scaleY = 1,
        NumericValue|Reference|float $scaleZ = 1,
        Renderable ...$renderable,
    ) {
        $this->renderables = $renderable;

        $this->scaleX = $this->convertToValue($scaleX);
        $this->scaleY = $this->convertToValue($scaleY);
        $this->scaleZ = $this->convertToValue($scaleZ);
    }

    protected function doRender(): string
    {
        $usefulWrapper = !$this->scaleX->hasLiteralValue()
            || !$this->scaleY->hasLiteralValue()
            || !$this->scaleZ->hasLiteralValue()
            || (int) $this->scaleX->getValue() !== 1
            || (int) $this->scaleY->getValue() !== 1
            || (int) $this->scaleZ->getValue() !== 1
        ;

        $result = '';
        if ($usefulWrapper) {
            $result .= "scale([{$this->scaleX}, {$this->scaleY}, {$this->scaleZ}]) {";
        }
        $result .= $this->renderRenderables();
        if ($usefulWrapper) {
            $result .= '}';
        }

        return $result;
    }
}
