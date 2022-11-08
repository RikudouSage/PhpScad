<?php

namespace Rikudou\PhpScad\Shape;

use Rikudou\PhpScad\FacetsConfiguration\FacetsConfiguration;
use Rikudou\PhpScad\Implementation\ConditionalRenderable;
use Rikudou\PhpScad\Implementation\FacetsConfigImplementation;
use Rikudou\PhpScad\Implementation\RenderableImplementation;
use Rikudou\PhpScad\Implementation\ValueConverter;
use Rikudou\PhpScad\Implementation\Wither;
use Rikudou\PhpScad\Module\NonCenterableCylinderModule;
use Rikudou\PhpScad\Primitive\HasFacetsConfig;
use Rikudou\PhpScad\Primitive\HasModuleDefinitions;
use Rikudou\PhpScad\Primitive\HasWrappers;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Value\BoolValue;
use Rikudou\PhpScad\Value\NullValue;
use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;
use Rikudou\PhpScad\Value\Value;

final class Cylinder implements Renderable, HasFacetsConfig, HasModuleDefinitions, HasWrappers
{
    use RenderableImplementation;
    use FacetsConfigImplementation;
    use ConditionalRenderable;
    use Wither;
    use ValueConverter;

    private NumericValue|Reference $height;

    private NumericValue|Reference|NullValue $radius;

    private NumericValue|Reference|NullValue $bottomRadius;

    private NumericValue|Reference|NullValue $topRadius;

    private NumericValue|Reference|NullValue $diameter;

    private NumericValue|Reference|NullValue $bottomDiameter;

    private NumericValue|Reference|NullValue $topDiameter;

    private BoolValue|Reference $centerOnZ;

    private BoolValue|Reference $centerOnXY;

    public function __construct(
        NumericValue|Reference|float $height = 0,
        NumericValue|Reference|NullValue|float|null $radius = null,
        NumericValue|Reference|NullValue|float|null $bottomRadius = null,
        NumericValue|Reference|NullValue|float|null $topRadius = null,
        NumericValue|Reference|NullValue|float|null $diameter = null,
        NumericValue|Reference|NullValue|float|null $bottomDiameter = null,
        NumericValue|Reference|NullValue|float|null $topDiameter = null,
        BoolValue|Reference|bool $centerOnZ = false,
        BoolValue|Reference|bool $centerOnXY = true,
        ?FacetsConfiguration $facetsConfiguration = null,
    ) {
        $this->facetsConfiguration = $facetsConfiguration;

        $this->height = $this->convertToValue($height);
        $this->radius = $this->convertToValue($radius);
        $this->bottomRadius = $this->convertToValue($bottomRadius);
        $this->topRadius = $this->convertToValue($topRadius);
        $this->diameter = $this->convertToValue($diameter);
        $this->bottomDiameter = $this->convertToValue($bottomDiameter);
        $this->topDiameter = $this->convertToValue($topDiameter);
        $this->centerOnZ = $this->convertToValue($centerOnZ);
        $this->centerOnXY = $this->convertToValue($centerOnXY);
    }

    public function withHeight(NumericValue|Reference|float $height): self
    {
        return $this->with('height', $this->convertToValue($height));
    }

    public function withRadius(NumericValue|Reference|NullValue|float|null $radius): self
    {
        return $this->with('radius', $this->convertToValue($radius));
    }

    public function withBottomRadius(NumericValue|Reference|NullValue|float|null $bottomRadius): self
    {
        return $this->with('bottomRadius', $this->convertToValue($bottomRadius));
    }

    public function withTopRadius(NumericValue|Reference|NullValue|float|null $topRadius): self
    {
        return $this->with('topRadius', $this->convertToValue($topRadius));
    }

    public function withDiameter(NumericValue|Reference|NullValue|float|null $diameter): self
    {
        return $this->with('diameter', $this->convertToValue($diameter));
    }

    public function withBottomDiameter(NumericValue|Reference|NullValue|float|null $bottomDiameter): self
    {
        return $this->with('bottomDiameter', $this->convertToValue($bottomDiameter));
    }

    public function withTopDiameter(NumericValue|Reference|NullValue|float|null $topDiameter): self
    {
        return $this->with('topDiameter', $this->convertToValue($topDiameter));
    }

    public function withCenterOnZ(BoolValue|bool $center): self
    {
        return $this->with('centerOnZ', $this->convertToValue($center));
    }

    public function withCenterOnXY(BoolValue|bool $center): self
    {
        return $this->with('centerOnXY', $this->convertToValue($center));
    }

    public function getModules(): iterable
    {
        yield new NonCenterableCylinderModule();
    }

    protected function doRender(): string
    {
        if (
            !$this->radius instanceof NullValue
            && (
                !$this->bottomRadius instanceof NullValue
                || !$this->topRadius instanceof NullValue
            )
        ) {
            trigger_error(
                'You should not specify bottom radius or top radius if you specify radius',
                E_USER_WARNING,
            );
        }

        if (
            !$this->diameter instanceof NullValue
            && (
                !$this->topDiameter instanceof NullValue
                || !$this->bottomDiameter instanceof NullValue
            )
        ) {
            trigger_error(
                'You should not specify bottom diameter or top diameter if you specify diameter',
                E_USER_WARNING,
            );
        }

        $matrix = [
            [$this->radius, $this->bottomRadius, $this->topRadius],
            [$this->diameter, $this->bottomDiameter, $this->topDiameter],
        ];

        $unitsCount = array_reduce($matrix, function (int $carry, array $item) {
            return $carry + (count(array_filter($item, fn (Value $value) => !$value instanceof NullValue)) ? 1 : 0);
        }, 0);

        if ($unitsCount > 1) {
            trigger_error(
                'You should not specify both radius and diameter',
                E_USER_WARNING,
            );
        }

        $content = 'PhpScad_NonCenterableCylinder(';

        $content .= "h = {$this->height},";
        $content .= sprintf('center = %s,', $this->centerOnZ);
        $content .= sprintf('centerXY = %s,', $this->centerOnXY);

        if (($radius = $this->radius) !== null) {
            $content .= "r = {$radius},";
        }

        if (($radius = $this->bottomRadius) !== null) {
            $content .= "r1 = {$radius},";
        }

        if (($radius = $this->topRadius) !== null) {
            $content .= "r2 = {$radius},";
        }

        if (($diameter = $this->diameter) !== null) {
            $content .= "d = {$diameter},";
        }

        if (($diameter = $this->topDiameter) !== null) {
            $content .= "d2 = {$diameter},";
        }

        if (($diameter = $this->bottomDiameter) !== null) {
            $content .= "d1 = {$diameter},";
        }

        $content = substr($content, 0, -1);

        if ($facetsParameters = $this->getFacetsParameters()) {
            $content .= ", {$facetsParameters}";
        }

        $content .= ');';

        return $content;
    }

    protected function isRenderable(): bool
    {
        return !(
            $this->height->hasLiteralValue()
            && $this->height->getValue() <= 0
            && $this->radius->hasLiteralValue()
            && ($this->radius->getValue() ?? 0) <= 0
            && $this->bottomRadius->hasLiteralValue()
            && ($this->bottomRadius->getValue() ?? 0) <= 0
            && $this->topRadius->hasLiteralValue()
            && ($this->topRadius->getValue() ?? 0) <= 0
            && $this->diameter->hasLiteralValue()
            && ($this->diameter->getValue() ?? 0) <= 0
            && $this->bottomDiameter->hasLiteralValue()
            && ($this->bottomDiameter->getValue() ?? 0) <= 0
            && $this->topDiameter->hasLiteralValue()
            && ($this->topDiameter->getValue() ?? 0) <= 0
        );
    }
}
