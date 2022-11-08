<?php

namespace Rikudou\PhpScad\Primitive;

interface HasModuleDefinitions
{
    /**
     * @return iterable<Module>
     */
    public function getModules(): iterable;
}
