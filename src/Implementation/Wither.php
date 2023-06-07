<?php

namespace Rikudou\PhpScad\Implementation;

use Error;
use ReflectionObject;

trait Wither
{
    protected function with(string $property, mixed $value): self
    {
        $clone = clone $this;

        try {
            $clone->{$property} = $value;
        } catch (Error $e) {
            if (!str_contains($e->getMessage(), 'Cannot modify readonly property')) {
                throw $e;
            }

            $reflection = new ReflectionObject($this);
            $clone = $reflection->newInstanceWithoutConstructor();

            foreach ($reflection->getProperties() as $reflectionProperty) {
                if ($reflectionProperty->getName() === $property) {
                    $reflectionProperty->setValue($clone, $value);
                } else {
                    $reflectionProperty->setValue($clone, $reflectionProperty->getValue($this));
                }
            }
        }

        return $clone;
    }
}
