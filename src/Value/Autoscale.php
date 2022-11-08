<?php

namespace Rikudou\PhpScad\Value;

use Rikudou\PhpScad\Implementation\ValueConverter;

final class Autoscale extends Literal
{
    use ValueConverter;

    public function __construct(
        BoolValue|Reference|bool $autoscaleWidth = false,
        BoolValue|Reference|bool $autoscaleDepth = false,
        BoolValue|Reference|bool $autoscaleHeight = false,
    ) {
        parent::__construct([$autoscaleWidth, $autoscaleDepth, $autoscaleHeight]);
    }

    public function getValue(): array
    {
        return parent::getValue();
    }

    public function getScadRepresentation(): string
    {
        $value = $this->getValue();

        return "[{$this->convertToValue($value[0])}, {$this->convertToValue($value[1])}, {$this->convertToValue($value[2])}]";
    }
}
