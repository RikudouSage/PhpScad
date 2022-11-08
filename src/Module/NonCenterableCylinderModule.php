<?php

namespace Rikudou\PhpScad\Module;

use Rikudou\PhpScad\Primitive\Module;

final class NonCenterableCylinderModule implements Module
{
    public function getName(): string
    {
        return 'PhpScad_NonCenterableCylinder';
    }

    public function getDefinition(): string
    {
        return "module {$this->getName()}(h = undef, r = undef, r1 = undef, r2 = undef, d = undef, d1 = undef, d2 = undef, center = false, centerXY = true) {
            radii = [
                r == undef ? 0 : r,
                r1 == undef ? 0 : r1,
                r2 == undef ? 0 : r2,
                d == undef ? 0 : d / 2,
                d1 == undef ? 0 : d1 / 2,
                d2 == undef ? 0 : d2 / 2,
            ];
            move = centerXY ? 0 : max(radii);
            
            translate([move, move])
            cylinder(h = h, r = r, r1 = r1, r2 = r2, d = d, d1 = d1, d2 = d2, center = center);
        }";
    }
}
