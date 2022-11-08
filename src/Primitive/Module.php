<?php

namespace Rikudou\PhpScad\Primitive;

interface Module
{
    public function getName(): string;

    public function getDefinition(): string;
}
