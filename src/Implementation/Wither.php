<?php

namespace Rikudou\PhpScad\Implementation;

trait Wither
{
    protected function with(string $property, mixed $value): self
    {
        $clone = clone $this;
        $clone->{$property} = $value;

        return $clone;
    }
}
