<?php

namespace Rikudou\PhpScad\Transformation;

use Rikudou\PhpScad\FacetsConfiguration\FacetsConfiguration;
use Rikudou\PhpScad\Implementation\ConditionalRenderable;
use Rikudou\PhpScad\Implementation\FacetsConfigImplementation;
use Rikudou\PhpScad\Implementation\RenderableImplementation;
use Rikudou\PhpScad\Implementation\ValueConverter;
use Rikudou\PhpScad\Primitive\HasWrappers;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Primitive\TwoDimensionalShape;
use Rikudou\PhpScad\Value\BoolValue;
use Rikudou\PhpScad\Value\IntValue;
use Rikudou\PhpScad\Value\NullValue;
use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;

final class LinearExtrude implements Renderable, HasWrappers
{
    use RenderableImplementation;
    use ConditionalRenderable;
    use FacetsConfigImplementation;
    use ValueConverter;

    private NumericValue|Reference $height;

    private BoolValue|Reference $center;

    private IntValue|Reference|NullValue $convexity;

    private NumericValue|Reference $twist;

    private IntValue|Reference|NullValue $slices;

    private NumericValue|Reference $scale;

    /**
     * @var array<TwoDimensionalShape>
     */
    private array $shapes;

    /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
    public function __construct(
        NumericValue|Reference|float $height,
        BoolValue|Reference|bool $center = false,
        IntValue|Reference|NullValue|int|null $convexity = null,
        NumericValue|Reference|float $twist = 0,
        IntValue|Reference|NullValue|int|null $slices = null,
        NumericValue|Reference|float $scale = 1,
        ?FacetsConfiguration $facetsConfiguration = null,
        TwoDimensionalShape ...$shape,
    ) {
        $this->facetsConfiguration = $facetsConfiguration;

        $this->height = $this->convertToValue($height);
        $this->center = $this->convertToValue($center);
        $this->convexity = $this->convertToValue($convexity);
        $this->twist = $this->convertToValue($twist);
        $this->slices = $this->convertToValue($slices);
        $this->scale = $this->convertToValue($scale);
        $this->shapes = $shape;
    }

    protected function doRender(): string
    {
        $content = 'linear_extrude(';
        $content .= "height = {$this->height},";
        $content .= "center = {$this->center},";
        if (!$this->convexity instanceof NullValue) {
            $content .= "convexity = {$this->convexity},";
        }
        $content .= "twist = {$this->twist},";
        if (!$this->slices instanceof NullValue) {
            $content .= "slices = {$this->slices},";
        }
        $content .= "scale = {$this->scale},";
        if ($facetsParameters = $this->getFacetsParameters()) {
            $content .= "{$facetsParameters},";
        }
        $content = substr($content, 0, -1);
        $content .= ') {';
        foreach ($this->shapes as $shape) {
            $content .= $shape->render();
        }
        $content .= '}';

        return $content;
    }

    protected function isRenderable(): bool
    {
        return count($this->shapes)
            && ($this->height instanceof Reference || $this->height->getValue() > 0);
    }
}
