<?php

namespace Rikudou\PhpScad\Renderer;

final class StlRenderer extends AbstractOpenScadBinaryRenderer
{
    public function __construct(
        ?string $openScadPath = null,
        ?string $exportFormat = 'stl',
    ) {
        $this->setBinary($openScadPath ?? $this->findBinary());
        $this->exportFormat = $exportFormat;
    }

    protected function getArguments(): array
    {
        return [
            '--export-format', $this->exportFormat,
            '-o', self::TARGET_FILE_PLACEHOLDER,
            self::SCAD_FILE_PLACEHOLDER,
        ];
    }
}
