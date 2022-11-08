<?php

namespace Rikudou\PhpScad\Value;

use InvalidArgumentException;

final class Face extends Literal
{
    public function __construct(
        int|Point ...$point,
    ) {
        parent::__construct($point);
    }

    /**
     * @return array<int|Point>
     */
    public function getValue(): array
    {
        return parent::getValue();
    }

    public function getScadRepresentation(?PointVector &$points = null): string
    {
        $value = $this->getValue();
        if (count($value) < 3) {
            throw new InvalidArgumentException('A polyhedron face must have at least 3 points');
        }
        $points ??= new PointVector();

        $result = '[';
        foreach ($value as $point) {
            if (is_int($point)) {
                $result .= "{$point},";
            } else {
                $result .= "{$this->getPointIndex($point, $points)},";
            }
        }
        $result = substr($result, 0, -1);

        $result .= ']';

        return $result;
    }

    private function getPointIndex(Point $pointToFind, PointVector &$points): int
    {
        foreach ($points as $index => $point) {
            if ($point->getScadRepresentation() === $pointToFind->getScadRepresentation()) {
                return $index;
            }
        }

        $points = $points->withPoint($pointToFind);

        return $this->getPointIndex($pointToFind, $points);
    }
}
