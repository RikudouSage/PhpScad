<?php

namespace Rikudou\PhpScad\Customizer;

use Rikudou\PhpScad\Primitive\CustomizerVariable;

abstract class AbstractCustomizerVariable implements CustomizerVariable
{
    public function __construct(
        private readonly string $name,
        private readonly string|int|float|bool|array|null $value,
        private readonly ?string $description = null,
    ) {
    }

    public function __toString(): string
    {
        return $this->getScadRepresentation();
    }

    public function getName(): string
    {
        $name = $this->name;
        if (!str_starts_with($name, '$')) {
            $name = "\${$name}";
        }

        return $name;
    }

    public function getValue(): string|int|float|bool|array|null
    {
        return $this->value;
    }

    public function getScadRepresentation(): string
    {
        return $this->getRepresentation($this->getValue());
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    private function getRepresentation(float|array|bool|int|string|null $value): string
    {
        if (is_array($value)) {
            $result = '[';

            $result .= implode(', ', array_map(function (float|array|bool|int|string|null $value) {
                return $this->getRepresentation($value);
            }, $value));

            $result .= ']';

            return $result;
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_string($value)) {
            return "\"{$value}\"";
        }

        if ($value === null) {
            return 'undef';
        }

        return (string) $value;
    }
}
