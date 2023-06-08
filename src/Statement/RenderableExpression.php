<?php

namespace Rikudou\PhpScad\Statement;

use Rikudou\PhpScad\Implementation\ConditionalRenderable;
use Rikudou\PhpScad\Implementation\RenderableImplementation;
use Rikudou\PhpScad\Primitive\HasModuleDefinitions;
use Rikudou\PhpScad\Primitive\HasWrappers;
use Rikudou\PhpScad\Primitive\Module;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Value\Expression;

final class RenderableExpression implements Renderable, HasWrappers, HasModuleDefinitions
{
    use RenderableImplementation;
    use ConditionalRenderable;

    /**
     * @param array<Module> $modules
     */
    public function __construct(
        private readonly Expression|string $expression,
        private readonly array $modules = [],
    ) {
    }

    public function getModules(): iterable
    {
        return $this->modules;
    }

    protected function doRender(): string
    {
        $expression = $this->expression;
        if ($expression instanceof Expression) {
            $expression = substr($expression, 1, -1);
        }

        return $expression;
    }

    protected function isRenderable(): bool
    {
        return (string) $this->expression !== '';
    }
}
