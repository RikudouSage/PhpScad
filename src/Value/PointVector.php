<?php

namespace Rikudou\PhpScad\Value;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

final class PointVector extends Literal implements IteratorAggregate
{
    public function __construct(
        Point ...$point,
    ) {
        parent::__construct($point);
    }

    /**
     * @return array<Point>
     */
    public function getValue(): array
    {
        return parent::getValue();
    }

    public function getScadRepresentation(): string
    {
        $value = $this->getValue();

        $result = '[';
        foreach ($value as $item) {
            $result .= $item->getScadRepresentation() . ',';
        }
        if (count($value)) {
            $result = substr($result, 0, -1);
        }

        $result .= ']';

        return $result;
    }

    public static function fromArray(array $raw): self
    {
        $points = array_map(function (array|Point $item): Point {
            return $item instanceof Point ? $item : new Point(...$item);
        }, $raw);

        return new self(...$points);
    }

    /**
     * @return Traversable<Point>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->getValue());
    }

    public function withPoint(Point $point): self
    {
        $points = $this->getValue();
        $points[] = $point;

        return new self(...$points);
    }
}
