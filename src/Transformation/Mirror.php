<?php

namespace Rikudou\PhpScad\Transformation;

use Rikudou\PhpScad\Implementation\RenderableImplementation;
use Rikudou\PhpScad\Implementation\WrapperModuleDefinitions;
use Rikudou\PhpScad\Implementation\WrapperRenderableImplementation;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Primitive\WrapperRenderable;
use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;

final class Mirror implements WrapperRenderable
{
    use RenderableImplementation;
    use WrapperModuleDefinitions;
    use WrapperRenderableImplementation;

    private NumericValue|Reference $mirrorX;

    private NumericValue|Reference $mirrorY;

    private NumericValue|Reference $mirrorZ;

    public function __construct(
        NumericValue|Reference|float $mirrorX = 1,
        NumericValue|Reference|float $mirrorY = 1,
        NumericValue|Reference|float $mirrorZ = 1,
        Renderable ...$renderable,
    ) {
        $this->renderables = $renderable;

        $this->mirrorX = $this->convertToValue($mirrorX);
        $this->mirrorY = $this->convertToValue($mirrorY);
        $this->mirrorZ = $this->convertToValue($mirrorZ);
    }

    protected function doRender(): string
    {
        $usefulWrapper = !$this->mirrorX->hasLiteralValue()
            || !$this->mirrorY->hasLiteralValue()
            || !$this->mirrorZ->hasLiteralValue()
            || (int) $this->mirrorX->getValue() !== 1
            || (int) $this->mirrorY->getValue() !== 1
            || (int) $this->mirrorZ->getValue() !== 1;

        $result = '';
        if ($usefulWrapper) {
            $result .= "mirror([{$this->mirrorX}, {$this->mirrorY}, {$this->mirrorZ}]) {";
        }
        $result .= $this->renderRenderables();
        if ($usefulWrapper) {
            $result .= '}';
        }

        return $result;
    }
}
