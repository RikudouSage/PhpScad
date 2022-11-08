<?php

namespace Rikudou\PhpScad\Implementation;

trait ConditionalRenderable
{
    public function render(): string
    {
        if ($this->isRenderable()) {
            return $this->doRender();
        }

        return '';
    }

    abstract protected function doRender(): string;

    abstract protected function isRenderable(): bool;
}
