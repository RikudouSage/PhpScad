<?php

namespace Rikudou\PhpScad\Shape;

use JetBrains\PhpStorm\Immutable;
use Rikudou\PhpScad\FacetsConfiguration\FacetsConfiguration;
use Rikudou\PhpScad\Implementation\ConditionalRenderable;
use Rikudou\PhpScad\Implementation\FacetsConfigImplementation;
use Rikudou\PhpScad\Implementation\RenderableImplementation;
use Rikudou\PhpScad\Implementation\ValueConverter;
use Rikudou\PhpScad\Implementation\Wither;
use Rikudou\PhpScad\Module\NonCenterableSphereModule;
use Rikudou\PhpScad\Primitive\HasFacetsConfig;
use Rikudou\PhpScad\Primitive\HasModuleDefinitions;
use Rikudou\PhpScad\Primitive\HasWrappers;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Value\BoolValue;
use Rikudou\PhpScad\Value\NullValue;
use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;

#[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
final class Sphere implements Renderable, HasFacetsConfig, HasModuleDefinitions, HasWrappers
{
    use FacetsConfigImplementation;
    use RenderableImplementation;
    use ConditionalRenderable;
    use Wither;
    use ValueConverter;

    private Reference|NumericValue|NullValue $radius;

    private Reference|NumericValue|NullValue $diameter;

    private Reference|BoolValue $center;

    /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
    public function __construct(
        Reference|NumericValue|NullValue|float|null $radius = null,
        Reference|NumericValue|NullValue|float|null $diameter = null,
        Reference|BoolValue|bool $center = true,
        ?FacetsConfiguration $facetsConfiguration = null,
    ) {
        $this->facetsConfiguration = $facetsConfiguration;

        $this->radius = $this->convertToValue($radius);
        $this->diameter = $this->convertToValue($diameter);
        $this->center = $this->convertToValue($center);
    }

    public function getRadius(): NullValue|Reference|NumericValue
    {
        return $this->radius;
    }

    public function getDiameter(): NullValue|Reference|NumericValue
    {
        return $this->diameter;
    }

    public function getCenter(): Reference|BoolValue
    {
        return $this->center;
    }

    public function withRadius(Reference|NumericValue|NullValue|float|null $radius): self
    {
        return $this->with('radius', $this->convertToValue($radius));
    }

    public function withDiameter(Reference|NumericValue|NullValue|float|null $diameter): self
    {
        return $this->with('diameter', $this->convertToValue($diameter));
    }

    public function withCentered(bool|BoolValue $centered): self
    {
        return $this->with('center', $this->convertToValue($centered));
    }

    public function getModules(): iterable
    {
        yield new NonCenterableSphereModule();
    }

    protected function isRenderable(): bool
    {
        return (!$this->radius->hasLiteralValue() || (!$this->radius instanceof NullValue && $this->radius->getValue() > 0))
            || (!$this->diameter->hasLiteralValue() || (!$this->diameter instanceof NullValue && $this->diameter->getValue() > 0))
        ;
    }

    protected function doRender(): string
    {
        $content = 'PhpScad_NonCenterableSphere(';
        if (!$this->radius instanceof NullValue) {
            $parameter = 'r';
        } else {
            $parameter = 'd';
        }
        $content .= "{$parameter} = ";
        $content .= !$this->radius instanceof NullValue ? $this->radius : $this->diameter;
        $content .= ", center = {$this->center}";

        if ($facetsParameters = $this->getFacetsParameters()) {
            $content .= ", {$facetsParameters}";
        }
        $content .= ');';

        return $content;
    }
}
