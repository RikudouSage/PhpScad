<?php

namespace Rikudou\PhpScad\Combination;

use Rikudou\PhpScad\Implementation\RenderableImplementation;
use Rikudou\PhpScad\Implementation\WrapperModuleDefinitions;
use Rikudou\PhpScad\Implementation\WrapperRenderableImplementation;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Primitive\WrapperRenderable;

final class RenderableContainer implements WrapperRenderable
{
    use RenderableImplementation;
    use WrapperModuleDefinitions;
    use WrapperRenderableImplementation;

    public function __construct(
        Renderable ...$renderable,
    ) {
        $this->renderables =  $renderable;
    }

    protected function doRender(): string
    {
        return $this->renderRenderables();
    }
}
