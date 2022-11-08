<?php

namespace Rikudou\PhpScad\Module;

use Rikudou\PhpScad\Primitive\Module;

final class NonCenterableSphereModule implements Module
{
    public function getName(): string
    {
        return 'PhpScad_NonCenterableSphere';
    }

    public function getDefinition(): string
    {
        return "module {$this->getName()}(r = undef, d = undef, center = true) {
            move = center ? 0 : r != undef ? r : d != undef ? d / 2 : 0;
            translate([move, move, move])
            sphere(r = r, d = d);
        }";
    }
}
