<?php

namespace Rikudou\PhpScad\Value;

use Rikudou\PhpScad\Implementation\ValueConverter;

final class VectorValue extends Literal
{
    use ValueConverter;

    public function __construct(array $value)
    {
        parent::__construct($value);
    }

    public function getValue(): array
    {
        return parent::getValue();
    }

    public function getScadRepresentation(): string
    {
        $value = $this->getValue();
        $result = '[';

        foreach ($value as $item) {
            $result .= $this->convertToValue($item)->getScadRepresentation() . ',';
        }
        if (count($value)) {
            $result = substr($result, 0, -1);
        }

        $result .= ']';

        return $result;
    }
}
