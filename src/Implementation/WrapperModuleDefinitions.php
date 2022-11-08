<?php

namespace Rikudou\PhpScad\Implementation;

use Rikudou\PhpScad\Primitive\HasModuleDefinitions;
use Rikudou\PhpScad\Primitive\Renderable;

trait WrapperModuleDefinitions
{
    public function getModules(): iterable
    {
        foreach ($this->getRenderables() as $renderable) {
            if ($renderable instanceof HasModuleDefinitions) {
                yield from $renderable->getModules();
            }
        }
    }

    /**
     * @return iterable<Renderable>
     */
    abstract protected function getRenderables(): iterable;
}
