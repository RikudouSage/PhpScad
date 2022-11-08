<?php

namespace Rikudou\PhpScad\FacetsConfiguration;

final class FacetsNumber implements FacetsConfiguration
{
    public function __construct(
        private readonly float $numberOfFragments,
    ) {
    }

    public function getMinimumFragmentAngle(): ?float
    {
        return null;
    }

    public function getMinimumFragmentSize(): ?float
    {
        return null;
    }

    public function getNumberOfFragments(): float
    {
        return $this->numberOfFragments;
    }
}
