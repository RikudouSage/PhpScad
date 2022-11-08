<?php

namespace Rikudou\PhpScad\Transformation;

use Rikudou\PhpScad\Implementation\RenderableImplementation;
use Rikudou\PhpScad\Implementation\WrapperModuleDefinitions;
use Rikudou\PhpScad\Implementation\WrapperRenderableImplementation;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Primitive\WrapperRenderable;
use Rikudou\PhpScad\Value\NullValue;
use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Point;
use Rikudou\PhpScad\Value\Reference;
use Rikudou\PhpScad\Value\VectorValue;

final class Rotate implements WrapperRenderable
{
    use RenderableImplementation;
    use WrapperModuleDefinitions;
    use WrapperRenderableImplementation;

    private VectorValue|NumericValue|Reference $rotation;

    private Point|VectorValue|NullValue|Reference $axis;

    public function __construct(
        VectorValue|NumericValue|Reference|array|float $rotation,
        Point|VectorValue|NullValue|Reference|array|null $axis = null,
        Renderable ...$renderable,
    ) {
        $this->renderables = $renderable;

        $this->rotation = $this->convertToValue($rotation);
        $this->axis = $this->convertToValue($axis);
    }

    public function withRotation(VectorValue|NumericValue|Reference|array|float $rotation): self
    {
        return $this->with('rotation', $this->convertToValue($rotation));
    }

    public function withAxis(Point|VectorValue|NullValue|Reference|array|null $axis): self
    {
        return $this->with('axis', $this->convertToValue($axis));
    }

    protected function doRender(): string
    {
        if ($this->rotation instanceof VectorValue && !$this->axis instanceof NullValue) {
            error_log(
                'Providing axis point when rotation is an array is pointless, the axis will be ignored.',
                E_USER_NOTICE,
            );
        }

        return "rotate(a = {$this->rotation}, v = {$this->axis}) {{$this->renderRenderables()}}";
    }
}
