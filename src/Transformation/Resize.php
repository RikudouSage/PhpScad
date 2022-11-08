<?php

namespace Rikudou\PhpScad\Transformation;

use Rikudou\PhpScad\Implementation\RenderableImplementation;
use Rikudou\PhpScad\Implementation\WrapperModuleDefinitions;
use Rikudou\PhpScad\Implementation\WrapperRenderableImplementation;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Primitive\WrapperRenderable;
use Rikudou\PhpScad\Value\Autoscale;
use Rikudou\PhpScad\Value\BoolValue;
use Rikudou\PhpScad\Value\NullValue;
use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;
use Rikudou\PhpScad\Value\VectorValue;

final class Resize implements WrapperRenderable
{
    use RenderableImplementation;
    use WrapperModuleDefinitions;
    use WrapperRenderableImplementation;

    private NumericValue|Reference $newWidth;

    private NumericValue|Reference $newDepth;

    private NumericValue|Reference $newHeight;

    private BoolValue|Reference|NullValue|Autoscale|VectorValue $autoscale;

    public function __construct(
        NumericValue|Reference|float $newWidth = 0,
        NumericValue|Reference|float $newDepth = 0,
        NumericValue|Reference|float $newHeight = 0,
        BoolValue|Reference|NullValue|Autoscale|VectorValue|bool|null|array $autoscale = null,
        Renderable ...$renderable,
    ) {
        $this->renderables = $renderable;

        $this->newWidth = $this->convertToValue($newWidth);
        $this->newDepth = $this->convertToValue($newDepth);
        $this->newHeight = $this->convertToValue($newHeight);
        $this->autoscale = $this->convertToValue($autoscale);
    }

    protected function doRender(): string
    {
        $usefulWrapper =
            !$this->newWidth->hasLiteralValue()
            || !$this->newDepth->hasLiteralValue()
            || !$this->newHeight->hasLiteralValue()
            || (float) $this->newWidth->getValue() !== 0.0
            || (float) $this->newDepth->getValue() !== 0.0
            || (float) $this->newHeight->getValue() !== 0.0
        ;

        $result = '';

        if ($usefulWrapper) {
            $result .= "resize(newsize = [{$this->newWidth}, {$this->newDepth}, {$this->newHeight}]";

            if (!$this->autoscale instanceof NullValue) {
                $result .= ", auto = {$this->autoscale}";
            }

            $result .= ') {';
        }

        $result .= $this->renderRenderables();

        if ($usefulWrapper) {
            $result .= '}';
        }

        return $result;
    }
}
