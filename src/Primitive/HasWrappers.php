<?php

namespace Rikudou\PhpScad\Primitive;

use Rikudou\PhpScad\Wrapper\WrapperConfiguration;

interface HasWrappers
{
    /**
     * @return iterable<WrapperConfiguration>
     */
    public function getWrappers(): iterable;
}
