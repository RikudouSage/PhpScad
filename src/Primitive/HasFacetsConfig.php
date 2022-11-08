<?php

namespace Rikudou\PhpScad\Primitive;

use Rikudou\PhpScad\FacetsConfiguration\FacetsConfiguration;

interface HasFacetsConfig
{
    public function getFacetsConfiguration(): ?FacetsConfiguration;

    public function withFacetsConfiguration(?FacetsConfiguration $configuration): self;
}
