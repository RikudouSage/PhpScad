<?php

namespace Rikudou\PhpScad\Renderer;

final class StlRenderer extends AbstractOpenScadBinaryRenderer
{
    public function __construct(
        ?string $openScadPath = null,
    ) {
        $this->setBinary($openScadPath ?? $this->findBinary());
    }

    protected function getArguments(): array
    {
        return [
            '--export-format', 'stl',
            '-o', self::TARGET_FILE_PLACEHOLDER,
            self::SCAD_FILE_PLACEHOLDER,
        ];
    }
}
