<?php

namespace Rikudou\PhpScad\Renderer;

use RuntimeException;

abstract class AbstractOpenScadBinaryRenderer implements Renderer
{
    protected const TARGET_FILE_PLACEHOLDER = '%targetFile%';

    protected const SCAD_FILE_PLACEHOLDER = '%scadFile%';

    protected string $binaryPath;

    public function render(string $outputFile, string $scadContent): void
    {
        try {
            $file = null;

            $arguments = implode(' ', array_map(function (string $argument) use ($scadContent, $outputFile, &$file) {
                if ($argument === self::TARGET_FILE_PLACEHOLDER) {
                    $argument = $outputFile;
                }
                if ($argument === self::SCAD_FILE_PLACEHOLDER) {
                    $renderer = new ScadFileRenderer();
                    $file = tempnam(sys_get_temp_dir(), 'PhpScad');
                    $renderer->render($file, $scadContent);

                    $argument = $file;
                }

                return "'{$argument}'";
            }, $this->getArguments()));
            $command = "'{$this->binaryPath}' {$arguments}";

            exec("{$command} 2>&1 >/dev/null", result_code: $exitCode);

            if ($exitCode !== 0) {
                throw new RuntimeException("Failed to call system command: '{$command}'");
            }
        } finally {
            if (is_string($file) && is_file($file)) {
                unlink($file);
            }
        }
    }

    abstract protected function getArguments(): array;

    protected function setBinary(string $binary): void
    {
        $this->binaryPath = $binary;
    }

    protected function findBinary(): string
    {
        exec('which openscad', $output, $exitCode);
        if ($exitCode !== 0) {
            throw new RuntimeException('Cannot find openscad in path');
        }

        return $output[0];
    }
}
