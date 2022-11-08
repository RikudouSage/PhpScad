<?php

namespace Rikudou\PhpScad\Implementation;

use Rikudou\PhpScad\Primitive\Renderable;

trait AliasShape
{
    use RenderableImplementation;
    use ConditionalRenderable;
    use WrapperModuleDefinitions;
    use GetWrappedRenderable;

    abstract protected function getAliasedShape(): Renderable;

    protected function isRenderable(): bool
    {
        return true;
    }

    protected function doRender(): string
    {
        return $this->getWrapped($this->getAliasedShape())->render();
    }

    protected function getRenderables(): iterable
    {
        yield $this->getAliasedShape();
    }
}
