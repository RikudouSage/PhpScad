<?php

namespace Rikudou\PhpScad\Customizer\Dropdown;

use LogicException;
use Rikudou\PhpScad\Customizer\AbstractCustomizerVariable;

/**
 * @template T of string|int|float
 */
abstract class AbstractDropdownCustomizerVariable extends AbstractCustomizerVariable
{
    /**
     * @param T        $value
     * @param array<T> $possibleValues
     */
    public function __construct(string $name, string|int|float $value, array $possibleValues, ?string $description = null)
    {
        parent::__construct($name, $value, $description, $this->createSpecification($possibleValues));
    }

    /**
     * @return T
     */
    public function getValue(): string|int|float
    {
        return parent::getValue();
    }

    /**
     * @param array<T> $possibleValues
     */
    private function createSpecification(array $possibleValues): string
    {
        if (!count($possibleValues)) {
            throw new LogicException('At least one option must be specified for a dropdown');
        }
        $result = '// [';
        $useKey = !array_is_list($possibleValues);

        foreach ($possibleValues as $key => $value) {
            $result .= $useKey ? "{$value}:{$key}, " : "{$value}, ";
        }
        $result = substr($result, 0, -2);
        $result .= ']';

        return $result;
    }
}
