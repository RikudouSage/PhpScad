<?php

namespace Rikudou\PhpScad\Value;

final class FaceVector extends Literal
{
    public function __construct(Face ...$face)
    {
        parent::__construct($face);
    }

    /**
     * @return array<Face>
     */
    public function getValue(): array
    {
        return parent::getValue();
    }

    public function getScadRepresentation(?PointVector &$points = null): string
    {
        $value = $this->getValue();

        $result = '[';
        foreach ($value as $item) {
            $result .= $item->getScadRepresentation($points) . ',';
        }
        if (count($value)) {
            $result = substr($result, 0, -1);
        }

        $result .= ']';

        return $result;
    }
}
