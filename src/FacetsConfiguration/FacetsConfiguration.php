<?php

namespace Rikudou\PhpScad\FacetsConfiguration;

interface FacetsConfiguration
{
    public function getMinimumFragmentAngle(): ?float;

    public function getMinimumFragmentSize(): ?float;

    public function getNumberOfFragments(): ?float;
}
