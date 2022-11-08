<?php

namespace Rikudou\PhpScad\Renderer;

final class PngPreviewRenderer extends AbstractOpenScadBinaryRenderer
{
    public function __construct(
        private readonly ?int $width = null,
        private readonly ?int $height = null,
        ?string $openScadPath = null,
    ) {
        $this->setBinary($openScadPath ?? $this->findBinary());
    }

    protected function getArguments(): array
    {
        $args = [
            '-o', self::TARGET_FILE_PLACEHOLDER,
            '--export-format', 'png',
        ];

        if ($this->width !== null && $this->height !== null) {
            $args[] = '--imgsize';
            $args[] = "{$this->width},{$this->height}";
        }

        $args[] = self::SCAD_FILE_PLACEHOLDER;

        return $args;
    }
}
