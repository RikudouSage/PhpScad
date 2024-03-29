<?php

namespace Rikudou\PhpScad\Shape;

use Rikudou\PhpScad\Implementation\ConditionalRenderable;
use Rikudou\PhpScad\Implementation\RenderableImplementation;
use Rikudou\PhpScad\Implementation\ValueConverter;
use Rikudou\PhpScad\Implementation\Wither;
use Rikudou\PhpScad\Primitive\HasWrappers;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Value\Face;
use Rikudou\PhpScad\Value\FaceVector;
use Rikudou\PhpScad\Value\IntValue;
use Rikudou\PhpScad\Value\PointVector;
use Rikudou\PhpScad\Value\Reference;

final class Polyhedron implements Renderable, HasWrappers
{
    use RenderableImplementation;
    use ConditionalRenderable;
    use ValueConverter;
    use Wither;

    private PointVector|Reference $points;

    private FaceVector|Reference $faces;

    private IntValue|Reference $convexity;

    /**
     * @param FaceVector|Reference|array<Face> $faces
     *
     * @noinspection PhpFieldAssignmentTypeMismatchInspection
     */
    public function __construct(
        PointVector|Reference|array $points = new PointVector(),
        FaceVector|Reference|array $faces = new FaceVector(),
        IntValue|Reference|int $convexity = 1,
    ) {
        $this->points = $this->convertToValue($points, mapEmptyArrayTo: PointVector::class);
        $this->faces = $this->convertToValue($faces, mapEmptyArrayTo: FaceVector::class);
        $this->convexity = $this->convertToValue($convexity);
    }

    public function getPoints(): Reference|PointVector
    {
        return $this->points;
    }

    public function getFaces(): FaceVector|Reference
    {
        return $this->faces;
    }

    public function getConvexity(): IntValue|Reference
    {
        return $this->convexity;
    }

    public function withPoints(PointVector|Reference|array $points): self
    {
        return $this->with('points', $this->convertToValue($points, mapEmptyArrayTo: PointVector::class));
    }

    public function withFaces(FaceVector|Reference|array $faces): self
    {
        return $this->with('faces', $this->convertToValue($faces, mapEmptyArrayTo: FaceVector::class));
    }

    public function withConvexity(IntValue|Reference|int $convexity): self
    {
        return $this->with('convexity', $this->convertToValue($convexity));
    }

    protected function doRender(): string
    {
        $points = clone $this->points;

        $result = 'polyhedron(';

        $result .= "faces = {$this->faces->getScadRepresentation($points)},";
        $result .= "points = {$points},";
        $result .= "convexity = {$this->convexity}";

        $result .= ');';

        return $result;
    }

    protected function isRenderable(): bool
    {
        return true;
    }
}
