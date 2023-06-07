<?php

namespace Rikudou\PhpScad\Font;

use Rikudou\PhpScad\Exception\UnspecifiedFontNameException;

final class CustomFont implements FontReference, InjectedFont
{
    public function __construct(
        private readonly string $path,
        private readonly ?string $logicalName = null,
    ) {
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getName(): string
    {
        return $this->logicalName
            ?? throw new UnspecifiedFontNameException("You're trying to use the font as a reference without providing the font's logical name.");
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
