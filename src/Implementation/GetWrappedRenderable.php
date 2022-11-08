<?php

namespace Rikudou\PhpScad\Implementation;

use Rikudou\PhpScad\Primitive\HasWrappers;
use Rikudou\PhpScad\Primitive\Renderable;

trait GetWrappedRenderable
{
    private function getWrapped(Renderable $renderable): Renderable
    {
        if (!$renderable instanceof HasWrappers) {
            return $renderable;
        }

        foreach ($renderable->getWrappers() as $config) {
            $renderable = $this->getWrapped(new ($config->class)(...$config->getArguments($renderable)));
        }

        return $renderable;
    }
}
