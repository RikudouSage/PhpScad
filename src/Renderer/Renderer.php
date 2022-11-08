<?php

namespace Rikudou\PhpScad\Renderer;

interface Renderer
{
    public function render(string $outputFile, string $scadContent): void;
}
