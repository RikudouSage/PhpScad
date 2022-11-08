# PhpScad - 3D modelling using OpenSCAD in PHP

Ever wanted to create a 3D model in php? Sure you did, *everyone* does. And now you can.

## How does it work?

It generates OpenSCAD code that in turn creates the STL 3D model. OpenScad basically looks like this:

```openscad
translate([10, 0, 0]) // translate([x, y, z]) - moves the model by given coordinates
cube([10, 10, 10]); // cube([width, depth, height])

difference() { // only the difference between child objects is rendered
    cube([5, 10, 15]);
    cylinder(10, 5, 5); // cylinder(height, bottomRadius, topRadius)
}
```

This is the resulting 3D model preview:

![OpenSCAD example](/doc/img/example-openscad.png)

### So why use php if you can already create the model using code?

1. PHP is full-blown general-purpose language with a lot of documentation around the internet
2. There are full-blown IDEs that help with code completion
3. PHP supports more paradigms, like object oriented programming
4. Saner parameter names - for example the `cylinder` signature is `cylinder(h, r, r1, r2, d, d1, d2, center)`
   compared to PhpScad version - `new Cylinder(height, radius, bottomRadius, topRadius, diameter, bottomDiameter, topDiameter)`
    - note that in both OpenSCAD and PhpScad version many of the parameters are optional
5. Created an interesting parametric shape? Cool, share it via composer because PHP has a package manager!

### So how does the example above look in PHP?

```php
<?php

use Rikudou\PhpScad\ScadModel;
use Rikudou\PhpScad\Shape\Cube;
use Rikudou\PhpScad\Combination\Difference;
use Rikudou\PhpScad\Shape\Cylinder;

$model = new ScadModel();

$model = $model
    ->withRenderable((new Cube(width: 10, depth: 10, height: 10))->movedRight(10)) // using named parameters
    ->withRenderable(new Difference(
        new Cube(5, 10, 15), // using positional parameters
        new Cylinder(height: 10, bottomRadius: 5, topRadius: 5),
    ));

$model->render(__DIR__ . '/output.scad');
```

You can notice the preview looks the same:

![Same example as above but in php](/doc/img/example-php-openscad.png)

Notice the convenience method `->movedRight()` which is one of the examples of what's possible in PHP but not in
OpenSCAD - a fluent api that's more natural and easy to think about.

You can also go the OpenSCAD way in PhpScad:

```php
<?php

use Rikudou\PhpScad\Transformation\Translate;
use Rikudou\PhpScad\Coordinate\XYZ;
use Rikudou\PhpScad\Shape\Cube;

new Translate(new XYZ(x: 10, y: 0, z: 0), new Cube(width: 10, depth: 10, height: 10));
```

![Manual translate](/doc/img/example-manual-translate.png)

## Installation

`composer require rikudou/php-scad:dev-master`

## Usage

### Shapes

Shapes are the base of all models. PhpScad provides the same basic shades as OpenSCAD does, namely:

- Cube
- Cylinder
- Polyhedron
- Sphere

Additional shapes provided by PhpScad:

- Pyramid

Basically all shapes can be created by combining the basic shapes.

#### Cube

Creates a cube.

**Parameters**

- `number $width` (default: 0)
- `number $depth` (default: 0)
- `number $height` (default: 0)

**Rendered if**: At least one of the parameters is non-zero or is a reference type.

**Example**:

```php
<?php

use Rikudou\PhpScad\Shape\Cube;

$cube = new Cube(width: 10, depth: 10, height: 10);
```

![Cube](/doc/img/example-cube.png)

#### Cylinder

Creates a cylinder.

**Parameters**

- `number $height` (default: 0)
- `?number $radius` (default: null)
- `?number $bottomRadius` (default: null)
- `?number $topRadius` (default: null)
- `?number $diameter` (default: null)
- `?number $bottomDiameter` (default: null)
- `?number $topDiameter` (default: null)
- `bool $centerOnZ` (default: false) - whether the model should be centered on the Z axis
- `bool $centerOnXY` (default: true) - whether the model should be centered on the X and Y axis
- `?FacetsConfiguration $facetsConfiguration` (default: null) - facets configuration, more below

You should either provide radius or diameter, not both. Also, you should provide either the singular parameter
(`$radius`, `$diameter`) or one or both of the top/bottom pair
(`$bottomRadius`/`$topRadius`, `$bottomDiameter`/`$topDiameter).

Using a pair of radii/diameters allows you to create a cone. 

If you provide invalid combination of radii, the behavior is undefined and depends on the OpenSCAD implementation as
PhpScad will generate the shape with all the parameters you provide.

**Rendered if**: Height is non-zero and at least one of the radius parameters is provided.

**Example**:

```php
<?php

use Rikudou\PhpScad\Shape\Cylinder;

// all these shapes are equivalent
$cylinderWithDiameter = new Cylinder(height: 10, diameter: 20);
$cylinderWithTopBottomDiameters = new Cylinder(height: 10, topDiameter: 20, bottomDiameter: 20);
$cylinderWithRadius = new Cylinder(height: 10, radius: 10);
$cylinderWithTopBottomRadii = new Cylinder(height: 10, topRadius: 10, bottomRadius: 10);

// cone
$cone = new Cylinder(height: 10, topRadius: 10, bottomRadius: 20);

// fully centered
$centered = new Cylinder(height: 10, diameter: 10, centerOnXY: true, centerOnZ: true);
```

![Basic cylinder](/doc/img/example-cylinder.png)

![Cylinder - cone](/doc/img/example-cylinder-cone.png)

![Cylinder - centered](/doc/img/example-cylinder-centered.png)

#### Polyhedron

The most versatile of shapes, allows you to define your own shapes using points and faces.
It can be used to create any regular or irregular shape.

**Parameters**

- `array|PointVector $points` (default: empty PointVector) - all points that the shape will consist of
- `array|FaceVector $faces` (default: empty FaceVector) - the faces the shape will consist of
- `int $convexity` (default: 1) - specifies the maximum number of faces a ray intersecting the object might penetrate, only used in preview mode

While arrays may be used for both points and faces, using the provided `PointVector` and `FaceVector` is recommended
for better readability. When using `FaceVector` you don't have to populate `PointVector` manually.

There are basically two ways to create a polyhedron, let's call them "the OpenSCAD way" and "the PhpScad way".

#### Polyhedron - the OpenSCAD way

> I can't think of any reason to use this instead of the other way, but it's supported for the sake of completeness, feel
> free to skip this part of the documentation.

First you need to define an array of points, you can either use `PointVector` or an array:

```php
<?php

use Rikudou\PhpScad\Value\PointVector;
use Rikudou\PhpScad\Value\Point;

// five points to make a pyramid, in no particular order
$points = [
    [10, 15, 0],
    [10, 0, 0],
    [5, 7.5, 20],
    [0, 0, 0],
    [0, 15, 0],
];

// using PointVector
$points = new PointVector(
    new Point(x: 10, y: 15, z: 0),
    new Point(x: 10, y: 0, z: 0),
    new Point(x: 5, y: 7.5, z: 20),
    new Point(x: 0, y: 0, z: 0),
    new Point(x: 0, y: 15, z: 0),
);
```

Then you need to reference those points when creating faces, using an index of the points in the points array:

> All faces must have their points ordered in **clockwise** direction when looking at each face from outside **inward**.

```php
<?php

use Rikudou\PhpScad\Shape\Polyhedron;
use Rikudou\PhpScad\Value\PointVector;
use Rikudou\PhpScad\Value\Point;
use Rikudou\PhpScad\Value\FaceVector;
use Rikudou\PhpScad\Value\Face;

// from previous example, added manual indexes for clarity
$points = [
    0 => [10, 15, 0],
    1 => [10, 0, 0],
    2 => [5, 7.5, 20],
    3 => [0, 0, 0],
    4 => [0, 15, 0],
];

$faces = [
    new Face(0, 1, 2), // points with indexes 0, 1 and 2, meaning '[10, 15, 0]', '[10, 0, 0]' and '[5, 7.5, 20]'
    new Face(1, 3, 2), // points with indexes 1, 3 and 2, meaning '[10, 0, 0]', '[0, 0, 0]' and '[5, 7.5, 20]'
    new Face(3, 4, 2), // '[0, 0, 0]', '[0, 15, 0]' and '[5, 7.5, 20]'
    new Face(4, 0, 2), // '[0, 15, 0]', '[10, 15, 0]', '[5, 7.5, 20]'
    new Face(0, 4, 3, 1), // '[10, 15, 0]', '[0, 15, 0]', '[0, 0, 0]', '[10, 0, 0]'
];

$polyhedron = new Polyhedron(points: $points, faces: $faces);

// or the same example using provided DTOs
$points = new PointVector(
    new Point(x: 10, y: 15, z: 0),
    new Point(x: 10, y: 0, z: 0),
    new Point(x: 5, y: 7.5, z: 20),
    new Point(x: 0, y: 0, z: 0),
    new Point(x: 0, y: 15, z: 0),
);

$faces = new FaceVector(
    new Face(0, 1, 2),
    new Face(1, 3, 2),
    new Face(3, 4, 2),
    new Face(4, 0, 2),
    new Face(0, 4, 3, 1),
);

$polyhedron = new Polyhedron(points: $points, faces: $faces);

// unless I made some mistake, $polyhedron should now hold a proper pyramid
```

![Polyhedron example](/doc/img/example-polyhedron.png)

As you can see this is hard to work with.

#### Polyhedron - the PhpScad way

Instead of the complicated stuff with referencing points by their indexes you can simply create the points when creating
faces, the points array will be defined internally:

```php
<?php

use Rikudou\PhpScad\Shape\Polyhedron;
use Rikudou\PhpScad\Value\Point;
use Rikudou\PhpScad\Value\Face;
use Rikudou\PhpScad\Value\FaceVector;

// this should be the same pyramid as above (unless I made a mistake)
$faces = new FaceVector(
    new Face(
        new Point(x: 10, y: 15, z: 0),
        new Point(x: 10, y: 0, z: 0),
        new Point(x: 5, y: 7.5, z: 20),
    ),
    new Face(
        new Point(x: 10, y: 0, z: 0),
        new Point(x: 0, y: 0, z: 0),
        new Point(x: 5, y: 7.5, z: 20),
    ),
    new Face(
        new Point(x: 0, y: 0, z: 0),
        new Point(x: 0, y: 15, z: 0),
        new Point(x: 5, y: 7.5, z: 20),
    ),
    new Face(
        new Point(x: 0, y: 15, z: 0),
        new Point(x: 10, y: 15, z: 0),
        new Point(x: 5, y: 7.5, z: 20),
    ),
    new Face(
        new Point(x: 10, y: 15, z: 0),
        new Point(x: 0, y: 15, z: 0),
        new Point(x: 0, y: 0, z: 0),
        new Point(x: 10, y: 0, z: 0),
    )
);

$polyhedron = new Polyhedron(faces: $faces);

// even more readable would be using good old variables like this:

$topPoint = new Point(x: 5, y: 7.5, z: 20);
$bottomLeft = new Point(x: 0, y: 0, z: 0);
$bottomRight = new Point(x: 10, y: 0, z: 0);
$topRight = new Point(x: 10, y: 15, z: 0);
$topLeft = new Point(x: 0, y: 15, z: 0);

// voila, perfectly readable
$polyhedron = new Polyhedron(faces: new FaceVector(
    new Face($topRight, $bottomRight, $topPoint),
    new Face($bottomRight, $bottomLeft, $topPoint),
    new Face($bottomLeft, $topLeft, $topPoint),
    new Face($topLeft, $topRight, $topPoint),
    new Face($topRight, $topLeft, $bottomLeft, $bottomRight),
));
```

![Polyhedron example](/doc/img/example-polyhedron.png)

#### Sphere

Creates a sphere.

**Parameters**

- `?number $radius` (default: null)
- `?number $diameter` (default: null)
- `bool $center` (default: true) - whether to center the sphere on axis X, Y and Z
- `?FacetsConfiguration $facetsConfiguration` (default: null) - facets configuration, more below

If both radius and diameter is specified, radius takes precedence.

**Rendered if**: At radius or diameter is non-zero or is a reference type.

**Example**:

```php
<?php

use Rikudou\PhpScad\Shape\Sphere;

$sphere = new Sphere(radius: 5);
$sphere = new Sphere(diameter: 10);
$sphere = new Sphere(radius: 5, center: false);
```

![Sphere](/doc/img/example-sphere.png)

![Non-centered sphere](/doc/img/example-sphere-non-centered.png)

#### Pyramid

Creates a pyramid.

**Parameters**

- `number $width`
- `number $depth`
- `number $height`

**Rendered if**: Always

**Example**:

```php
<?php

use Rikudou\PhpScad\Shape\Pyramid;

$pyramid = new Pyramid(width: 10, depth: 10, height: 10);
```

![Pyramid](/doc/img/example-pyramid.png)

### Facets configuration

A STL model is made of triangles which are very much incompatible with shapes like cylinders and spheres, so you have
to cheat a little - you create a sphere of many, many triangles until you're ok with the way the sphere looks.

And that's where the facets configuration comes in - it configures how spherical things will look.

Signatures:

- `new FacetsNumber(float $numberOfFragments)`
- `new FacetsAngleAndSize(float $angle, float $size)`

- `$numberOfFragments` - the circle is rendered using this number of fragments
- `$angle` - minimum angle for a fragment, no circle has more than 360 divided by this number
- `$size` - minimum size of a fragment

**Examples:**

Default:

```php
<?php

use Rikudou\PhpScad\Shape\Sphere;

$sphere = new Sphere(radius: 10);
```

![Default facets](/doc/img/sphere-facets-default.png);

Facets number 60:

```php
<?php

use Rikudou\PhpScad\Shape\Sphere;
use Rikudou\PhpScad\FacetsConfiguration\FacetsNumber;

$sphere = new Sphere(radius: 10, facetsConfiguration: new FacetsNumber(60));
```

![60 facets](/doc/img/sphere-fn-60.png);

Facets number 120:

```php
<?php

use Rikudou\PhpScad\Shape\Sphere;
use Rikudou\PhpScad\FacetsConfiguration\FacetsNumber;

$sphere = new Sphere(radius: 10, facetsConfiguration: new FacetsNumber(120));
```

![120 facets](/doc/img/sphere-fn-120.png);

Facets number 240:

```php
<?php

use Rikudou\PhpScad\Shape\Sphere;
use Rikudou\PhpScad\FacetsConfiguration\FacetsNumber;

$sphere = new Sphere(radius: 10, facetsConfiguration: new FacetsNumber(240));
```

![120 facets](/doc/img/sphere-fn-240.png);

Facets number 360:

```php
<?php

use Rikudou\PhpScad\Shape\Sphere;
use Rikudou\PhpScad\FacetsConfiguration\FacetsNumber;

$sphere = new Sphere(radius: 10, facetsConfiguration: new FacetsNumber(360));
```

![120 facets](/doc/img/sphere-fn-360.png);

As you can see, the more facets you use, the smoother the spherical stuff looks, but it also takes a longer time to render and
the object is more complex.

> Note: The facets configuration can be also set globally for the whole model instead of per shape basis

```php
<?php

use Rikudou\PhpScad\ScadModel;
use Rikudou\PhpScad\FacetsConfiguration\FacetsNumber;

$model = new ScadModel(facetsConfiguration: new FacetsNumber(30));
// all renderables will now use the above facets number as default
```

**This documentation is work in progress**