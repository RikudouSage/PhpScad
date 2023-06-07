<?php

namespace Rikudou\PhpScad\Shape\TwoDimensional;

use Rikudou\PhpScad\FacetsConfiguration\FacetsConfiguration;
use Rikudou\PhpScad\Implementation\ConditionalRenderable;
use Rikudou\PhpScad\Implementation\FacetsConfigImplementation;
use Rikudou\PhpScad\Implementation\TwoDimensionalShapeImplementation;
use Rikudou\PhpScad\Implementation\ValueConverter;
use Rikudou\PhpScad\Primitive\HasFacetsConfig;
use Rikudou\PhpScad\Primitive\TwoDimensionalShape;
use Rikudou\PhpScad\Value\NullValue;
use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;

final class Circle implements TwoDimensionalShape, HasFacetsConfig
{
    use ConditionalRenderable;
    use FacetsConfigImplementation;
    use ValueConverter;
    use TwoDimensionalShapeImplementation;

    private NumericValue|Reference|NullValue $radius;

    private NumericValue|Reference|NullValue $diameter;

    /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
    public function __construct(
        NumericValue|Reference|NullValue|float|null $radius = null,
        NumericValue|Reference|NullValue|float|null $diameter = null,
        ?FacetsConfiguration $facetsConfiguration = null,
    ) {
        $this->facetsConfiguration = $facetsConfiguration;

        $this->radius = $this->convertToValue($radius);
        $this->diameter = $this->convertToValue($diameter);
    }

    protected function doRender(): string
    {
        $content = 'circle(';
        if (!$this->radius instanceof NullValue) {
            $content .= "r = {$this->radius},";
        } else {
            $content .= "d = {$this->diameter},";
        }
        if ($facetsParameters = $this->getFacetsParameters()) {
            $content .= "{$facetsParameters},";
        }
        $content = substr($content, 0, -1);
        $content .= ');';

        return $content;
    }

    protected function isRenderable(): bool
    {
        if ($this->radius instanceof NullValue && $this->diameter instanceof NullValue) {
            return false;
        }

        if ($this->radius instanceof Reference || $this->radius->getValue() > 0) {
            return true;
        }
        if ($this->diameter instanceof Reference || $this->diameter->getValue() > 0) {
            return true;
        }

        return false;
    }
}
