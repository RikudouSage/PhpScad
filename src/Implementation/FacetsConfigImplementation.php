<?php

namespace Rikudou\PhpScad\Implementation;

use Rikudou\PhpScad\FacetsConfiguration\FacetsConfiguration;

trait FacetsConfigImplementation
{
    private ?FacetsConfiguration $facetsConfiguration;

    public function getFacetsConfiguration(): ?FacetsConfiguration
    {
        return $this->facetsConfiguration;
    }

    public function withFacetsConfiguration(?FacetsConfiguration $configuration): self
    {
        $clone = clone $this;
        $clone->facetsConfiguration = $configuration;

        return $clone;
    }

    protected function getFacetsParameters(): string
    {
        $content = '';

        if (($fa = $this->facetsConfiguration?->getMinimumFragmentAngle()) !== null) {
            $content .= "\$fa = {$fa},";
        }
        if (($fs = $this->facetsConfiguration?->getMinimumFragmentSize()) !== null) {
            $content .= "\$fs = {$fs},";
        }
        if (($fn = $this->facetsConfiguration?->getNumberOfFragments()) !== null) {
            $content .= "\$fn = {$fn},";
        }

        if ($content) {
            $content = substr($content, 0, -1);
        }

        return $content;
    }
}
