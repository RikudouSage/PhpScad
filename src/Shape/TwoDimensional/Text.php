<?php

namespace Rikudou\PhpScad\Shape\TwoDimensional;

use Rikudou\PhpScad\Enum\HorizontalAlignment;
use Rikudou\PhpScad\Enum\TextDirection;
use Rikudou\PhpScad\Enum\VerticalAlignment;
use Rikudou\PhpScad\FacetsConfiguration\FacetsConfiguration;
use Rikudou\PhpScad\Font\FontReference;
use Rikudou\PhpScad\Implementation\ConditionalRenderable;
use Rikudou\PhpScad\Implementation\FacetsConfigImplementation;
use Rikudou\PhpScad\Implementation\TwoDimensionalShapeImplementation;
use Rikudou\PhpScad\Implementation\ValueConverter;
use Rikudou\PhpScad\Implementation\Wither;
use Rikudou\PhpScad\Primitive\HasFacetsConfig;
use Rikudou\PhpScad\Primitive\TwoDimensionalShape;
use Rikudou\PhpScad\Value\NullValue;
use Rikudou\PhpScad\Value\NumericValue;
use Rikudou\PhpScad\Value\Reference;
use Rikudou\PhpScad\Value\StringValue;

final class Text implements TwoDimensionalShape, HasFacetsConfig
{
    use TwoDimensionalShapeImplementation;
    use ConditionalRenderable;
    use FacetsConfigImplementation;
    use ValueConverter;
    use Wither;

    private StringValue|Reference $text;

    private NumericValue|Reference $size;

    private StringValue|Reference|NullValue $font;

    private StringValue|Reference $horizontalAlign;

    private StringValue|Reference $verticalAlign;

    private NumericValue|Reference $spacing;

    private StringValue|Reference $direction;

    private StringValue|Reference $language;

    /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
    public function __construct(
        StringValue|Reference|string $text,
        NumericValue|Reference|float $size = 10,
        FontReference|StringValue|Reference|NullValue|string|null $font = null,
        HorizontalAlignment|StringValue|Reference|string $horizontalAlign = HorizontalAlignment::Left,
        VerticalAlignment|StringValue|Reference|string $verticalAlign = VerticalAlignment::Baseline,
        NumericValue|Reference|float $spacing = 1,
        TextDirection|StringValue|Reference|string $direction = TextDirection::LeftToRight,
        StringValue|Reference|string $language = 'en',
        ?FacetsConfiguration $facetsConfiguration = null,
    ) {
        $this->facetsConfiguration = $facetsConfiguration;

        $this->text = $this->convertToValue($text);
        $this->size = $this->convertToValue($size);
        $this->font = $this->convertToValue($font instanceof FontReference ? new StringValue($font->getName()) : $font);
        $this->horizontalAlign = $this->convertToValue($horizontalAlign);
        $this->verticalAlign = $this->convertToValue($verticalAlign);
        $this->spacing = $this->convertToValue($spacing);
        $this->direction = $this->convertToValue($direction);
        $this->language = $this->convertToValue($language);
    }

    public function getText(): Reference|StringValue
    {
        return $this->text;
    }

    public function getSize(): Reference|NumericValue
    {
        return $this->size;
    }

    public function getFont(): NullValue|Reference|StringValue
    {
        return $this->font;
    }

    public function getHorizontalAlign(): Reference|StringValue
    {
        return $this->horizontalAlign;
    }

    public function getVerticalAlign(): Reference|StringValue
    {
        return $this->verticalAlign;
    }

    public function getSpacing(): Reference|NumericValue
    {
        return $this->spacing;
    }

    public function getDirection(): Reference|StringValue
    {
        return $this->direction;
    }

    public function getLanguage(): Reference|StringValue
    {
        return $this->language;
    }

    public function withText(StringValue|Reference|string $text): self
    {
        return $this->with('text', $this->convertToValue($text));
    }

    public function withSize(NumericValue|Reference|float $size): self
    {
        $this->with('size', $this->convertToValue($size));
    }

    public function withFont(FontReference|StringValue|Reference|NullValue|string|null $font): self
    {
        return $this->with('font', $this->convertToValue($font instanceof FontReference ? new StringValue($font->getName()) : $font));
    }

    public function withHorizontalAlign(HorizontalAlignment|StringValue|Reference|string $horizontalAlign): self
    {
        return $this->with('horizontalAlign', $this->convertToValue($horizontalAlign));
    }

    public function withVerticalAlign(VerticalAlignment|StringValue|Reference|string $verticalAlign): self
    {
        return $this->with('verticalAlign', $this->convertToValue($verticalAlign));
    }

    public function withSpacing(NumericValue|Reference|float $spacing): self
    {
        return $this->with('spacing', $this->convertToValue($spacing));
    }

    public function withDirection(TextDirection|StringValue|Reference|string $direction): self
    {
        return $this->with('direction', $this->convertToValue($direction));
    }

    public function withLanguage(StringValue|Reference|string $language): self
    {
        return $this->with('language', $this->convertToValue($language));
    }

    protected function doRender(): string
    {
        $content = 'text(';
        $content .= "text = {$this->text},";
        $content .= "size = {$this->size},";
        if (!$this->font instanceof NullValue) {
            $content .= "font = {$this->font},";
        }
        $content .= "halign = {$this->horizontalAlign},";
        $content .= "valign = {$this->verticalAlign},";
        $content .= "spacing = {$this->spacing},";
        $content .= "direction = {$this->direction},";
        $content .= "language = {$this->language},";
        if ($facets = $this->getFacetsParameters()) {
            $content .= "{$facets},";
        }
        $content = substr($content, 0, -1);
        $content .= ');';

        return $content;
    }

    protected function isRenderable(): bool
    {
        return ($this->text instanceof Reference || strlen($this->text->getValue()) > 0)
            && ($this->size instanceof Reference || $this->size->getValue() > 0)
        ;
    }
}
