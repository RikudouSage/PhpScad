<?php

namespace Rikudou\PhpScad\Implementation;

use Rikudou\PhpScad\Primitive\Renderable;

trait WrapperRenderableImplementation
{
    use ConditionalRenderable;
    use GetWrappedRenderable;
    use Wither;
    use ValueConverter;

    /**
     * @var array<Renderable>
     */
    protected array $renderables = [];

    public function withRenderable(Renderable $renderable): self
    {
        $renderables = $this->renderables;
        $renderables[] = $renderable;

        return $this->with('renderables', $renderables);
    }

    /**
     * @return iterable<Renderable>
     */
    protected function getRenderables(): iterable
    {
        return $this->renderables;
    }

    protected function isRenderable(): bool
    {
        return count($this->renderables) > 0;
    }

    private function renderRenderables(): string
    {
        $result = '';
        foreach ($this->getRenderables() as $renderable) {
            $result .= $this->getWrapped($renderable)->render();
        }

        return $result;
    }

    private function renderUnwrappedRenderables(): string
    {
        $result = '';
        foreach ($this->getRenderables() as $renderable) {
            $result .= $renderable->render();
        }

        return $result;
    }
}
