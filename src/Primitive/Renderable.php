<?php

namespace Rikudou\PhpScad\Primitive;

use Rikudou\PhpScad\Color\Color;
use Rikudou\PhpScad\Coordinate\Coordinate;

interface Renderable
{
    public function getPosition(): Coordinate;

    public function withPosition(Coordinate $coordinate): self;

    public function getColor(): ?Color;

    public function withColor(?Color $color): self;

    public function render(): string;
}
