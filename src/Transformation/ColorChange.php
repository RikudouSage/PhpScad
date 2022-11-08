<?php

namespace Rikudou\PhpScad\Transformation;

use Rikudou\PhpScad\Color\Color;
use Rikudou\PhpScad\Implementation\RenderableImplementation;
use Rikudou\PhpScad\Implementation\WrapperModuleDefinitions;
use Rikudou\PhpScad\Implementation\WrapperRenderableImplementation;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Primitive\WrapperRenderable;
use Rikudou\PhpScad\Wrapper\WrapperConfiguration;
use Rikudou\PhpScad\Wrapper\WrapperValuePlaceholder;

final class ColorChange implements WrapperRenderable
{
    use RenderableImplementation;
    use WrapperModuleDefinitions;
    use WrapperRenderableImplementation;

    public function __construct(
        ?Color $color,
        Renderable ...$renderable,
    ) {
        $this->renderables = $renderable;
        $this->color = $color;
    }

    public function getWrappers(): iterable
    {
        yield new WrapperConfiguration(
            Translate::class,
            $this->getPosition(),
            new WrapperValuePlaceholder(),
        );
    }

    protected function doRender(): string
    {
        $result = '';
        if ($this->color !== null) {
            $result .= "color({$this->color}) {";
        }
        $result .= $this->renderUnwrappedRenderables();
        if ($this->color !== null) {
            $result .= '}';
        }

        return $result;
    }
}
