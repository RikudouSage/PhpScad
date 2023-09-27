<?php

namespace Rikudou\PhpScad\Primitive;

use Stringable;

interface CustomizerVariable extends Stringable
{
    public function getName(): string;

    public function getValue(): string|int|float|bool|array|null;

    public function getScadRepresentation(): string;

    public function getDescription(): ?string;

    public function getSpecification(): ?string;
}
