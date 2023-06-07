<?php

namespace Rikudou\PhpScad\Implementation;

use InvalidArgumentException;
use Rikudou\PhpScad\Value\BoolValue;
use Rikudou\PhpScad\Value\Face;
use Rikudou\PhpScad\Value\FaceVector;
use Rikudou\PhpScad\Value\FloatValue;
use Rikudou\PhpScad\Value\IntValue;
use Rikudou\PhpScad\Value\NullValue;
use Rikudou\PhpScad\Value\PiValue;
use Rikudou\PhpScad\Value\Point;
use Rikudou\PhpScad\Value\PointVector;
use Rikudou\PhpScad\Value\StringValue;
use Rikudou\PhpScad\Value\Value;
use Rikudou\PhpScad\Value\VectorValue;

trait ValueConverter
{
    private function convertToValue(
        Value|int|float|null|bool|array|string $value,
        string $mapEmptyArrayTo = VectorValue::class,
    ): Value {
        if ($value instanceof Value) {
            return $value;
        }
        if (is_int($value)) {
            return new IntValue($value);
        }
        if ($value === M_PI) {
            return new PiValue();
        }
        if (is_float($value)) {
            return new FloatValue($value);
        }
        if ($value === null) {
            return new NullValue();
        }
        if (is_bool($value)) {
            return new BoolValue($value);
        }
        if (is_string($value)) {
            return new StringValue($value);
        }

        if ($this->isPointsVector($value, is_a($mapEmptyArrayTo, PointVector::class, true))) {
            return PointVector::fromArray($value);
        }

        if ($this->isFaceVector($value, is_a($mapEmptyArrayTo, FaceVector::class, true))) {
            return new FaceVector(...$value);
        }

        if (is_array($value)) {
            return new VectorValue($value);
        }

        throw new InvalidArgumentException('Type not supported: ' . gettype($value));
    }

    private function isFaceVector(mixed $array, bool $considerEmptyArrayAsFaceVector): bool
    {
        if (!is_array($array)) {
            return false;
        }

        if (!count($array)) {
            return $considerEmptyArrayAsFaceVector;
        }

        if (!array_is_list($array)) {
            return false;
        }

        foreach ($array as $item) {
            if (!$item instanceof Face) {
                return false;
            }
        }

        return true;
    }

    private function isPointsVector(mixed $array, bool $considerEmptyArrayAsPointsVector): bool
    {
        if (!is_array($array)) {
            return false;
        }

        if (!count($array)) {
            return $considerEmptyArrayAsPointsVector;
        }

        if (!array_is_list($array)) {
            return false;
        }

        $invalidPoints = array_reduce($array, function (int $carry, mixed $item) {
            if ($item instanceof Point) {
                return $carry;
            }

            if (!is_array($item) || !array_is_list($item) || count($item) !== 3) {
                return $carry + 1;
            }

            foreach ($item as $value) {
                if (!is_int($value) && !is_float($value)) {
                    return $carry + 1;
                }
            }

            return $carry;
        }, 0);

        if ($invalidPoints) {
            return false;
        }

        return true;
    }
}
