<?php

namespace Rikudou\PhpScad\Wrapper;

use Rikudou\PhpScad\Implementation\RenderableImplementation;
use Rikudou\PhpScad\Implementation\WrapperModuleDefinitions;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Primitive\WrapperRenderable;

final class CommentWrapper implements WrapperRenderable
{
    use RenderableImplementation;
    use WrapperModuleDefinitions;

    public function __construct(
        private readonly string $comment,
        private readonly Renderable $renderable,
    ) {
    }

    public function render(): string
    {
        return "/* {$this->comment} */ {$this->renderable->render()}";
    }

    protected function getRenderables(): iterable
    {
        yield $this->renderable;
    }
}
