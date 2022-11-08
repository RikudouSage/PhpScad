<?php

namespace Rikudou\PhpScad\FacetsConfiguration;

final class FacetsAngleAndSize implements FacetsConfiguration
{
    public function __construct(
        private readonly float $angle,
        private readonly float $size,
    ) {
    }

    public function getMinimumFragmentAngle(): float
    {
        return $this->angle;
    }

    public function getMinimumFragmentSize(): float
    {
        return $this->size;
    }

    public function getNumberOfFragments(): ?float
    {
        return null;
    }
}
