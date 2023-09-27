<?php

namespace Rikudou\PhpScad\Customizer;

final class SliderCustomizerVariable extends AbstractCustomizerVariable
{
    public function __construct(string $name, float|int $value, float|int $max, float|int|null $step = null, float|int|null $min = null, ?string $description = null)
    {
        parent::__construct($name, $value, $description, $this->createSpecification($min, $max, $step));
    }

    public function getValue(): int|float
    {
        return parent::getValue();
    }

    private function createSpecification(float|int|null $min, float|int $max, float|int|null $step): string
    {
        if ($min === null && $step === null) {
            return "// [{$max}]";
        }

        if ($min !== null && $step === null) {
            return "// [{$min}:{$max}]";
        }

        if ($min === null && $step !== null) {
            return "// [0:{$step}:{$max}]";
        }

        return "// [{$min}:{$step}:{$max}]";
    }
}
