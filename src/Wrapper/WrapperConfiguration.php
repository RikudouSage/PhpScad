<?php

namespace Rikudou\PhpScad\Wrapper;

use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Primitive\WrapperRenderable;

final class WrapperConfiguration
{
    private readonly array $arguments;

    /**
     * @param class-string<WrapperRenderable> $class
     */
    public function __construct(
        public readonly string $class,
        mixed ...$arguments,
    ) {
        $this->arguments = $arguments;
    }

    public function getArguments(Renderable $renderable): array
    {
        $arguments = $this->arguments;

        foreach ($arguments as $key => $value) {
            if ($value instanceof WrapperValuePlaceholder) {
                $arguments[$key] = $renderable;
            }
        }

        return $arguments;
    }
}
