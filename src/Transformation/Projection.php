<?php

namespace Rikudou\PhpScad\Transformation;

use Rikudou\PhpScad\Implementation\ConditionalRenderable;
use Rikudou\PhpScad\Implementation\GetWrappedRenderable;
use Rikudou\PhpScad\Implementation\TwoDimensionalShapeImplementation;
use Rikudou\PhpScad\Implementation\ValueConverter;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Primitive\TwoDimensionalShape;
use Rikudou\PhpScad\Value\BoolValue;
use Rikudou\PhpScad\Value\Reference;

final class Projection implements TwoDimensionalShape
{
    use ConditionalRenderable;
    use ValueConverter;
    use GetWrappedRenderable;
    use TwoDimensionalShapeImplementation;

    private BoolValue|Reference $cut;

    /**
     * @var array<Renderable>
     */
    private array $renderables;

    /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
    public function __construct(
        BoolValue|Reference|bool $cut = false,
        Renderable ...$renderable,
    ) {
        $this->cut = $this->convertToValue($cut);
        $this->renderables = $renderable;
    }

    protected function doRender(): string
    {
        $content = "projection(cut = {$this->cut}) {";
        foreach ($this->renderables as $renderable) {
            $content .= $this->getWrapped($renderable)->render();
        }
        $content .= '}';

        return $content;
    }

    protected function isRenderable(): bool
    {
        return count($this->renderables) > 0;
    }
}
