<?php

namespace Rikudou\PhpScad\Renderer;

final class ScadFileRenderer implements Renderer
{
    public function render(string $outputFile, string $scadContent): void
    {
        file_put_contents($outputFile, $scadContent);
    }
}
