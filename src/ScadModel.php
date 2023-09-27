<?php

namespace Rikudou\PhpScad;

use JetBrains\PhpStorm\Immutable;
use Rikudou\PhpScad\FacetsConfiguration\FacetsConfiguration;
use Rikudou\PhpScad\Font\InjectedFont;
use Rikudou\PhpScad\Implementation\GetWrappedRenderable;
use Rikudou\PhpScad\Implementation\Wither;
use Rikudou\PhpScad\Module\CustomizerEndModule;
use Rikudou\PhpScad\Primitive\CustomizerVariable;
use Rikudou\PhpScad\Primitive\HasModuleDefinitions;
use Rikudou\PhpScad\Primitive\Module;
use Rikudou\PhpScad\Primitive\Renderable;
use Rikudou\PhpScad\Renderer\Renderer;
use Rikudou\PhpScad\Renderer\ScadFileRenderer;

#[Immutable]
final class ScadModel
{
    use GetWrappedRenderable;
    use Wither;

    /**
     * @var array<Module>
     */
    private readonly array $modules;

    /**
     * @param array<Renderable>         $renderables
     * @param array<Module>             $modules
     * @param array<CustomizerVariable> $variables
     * @param array<InjectedFont>       $customFonts
     */
    public function __construct(
        private readonly ?FacetsConfiguration $facetsConfiguration = null,
        private readonly Renderer $renderer = new ScadFileRenderer(),
        private readonly array $renderables = [],
        array $modules = [],
        private readonly array $variables = [],
        private readonly bool $configurableFacets = false,
        private readonly array $customFonts = [],
        private readonly bool $showGenerator = true,
    ) {
        foreach ($modules as $key => $module) {
            if ($key !== $module->getName()) {
                unset($modules[$key]);
                $modules[$module->getName()] = $module;
            }
        }

        $this->modules = $modules;
    }

    public function withRenderable(Renderable $shape): self
    {
        $renderables = $this->renderables;
        $renderables[] = $shape;

        return $this->with('renderables', $renderables);
    }

    public function withModule(Module $module): self
    {
        $modules = $this->modules;
        $modules[$module->getName()] = $module;

        return $this->with('modules', $modules);
    }

    public function withFacetsConfiguration(FacetsConfiguration $configuration): self
    {
        return $this->with('facetsConfiguration', $configuration);
    }

    public function withVariable(CustomizerVariable $variable): self
    {
        $variables = $this->variables;
        $variables[] = $variable;

        return $this->with('variables', $variables);
    }

    public function withFont(InjectedFont $font): self
    {
        $fonts = $this->customFonts;
        $fonts[] = $font;

        return $this->with('customFonts', $fonts);
    }

    public function withRenderer(Renderer $renderer): self
    {
        return $this->with('renderer', $renderer);
    }

    public function render(string $outputFile): void
    {
        $this->renderer->render($outputFile, $this->getScadContent());
    }

    /** @noinspection PhpConcatenationWithEmptyStringCanBeInlinedInspection */
    public function getScadContent(): string
    {
        $fonts = '';
        foreach ($this->customFonts as $customFont) {
            $fonts .= "use <{$customFont->getPath()}>\n";
        }

        $modules = $this->modules;
        $customizerEndModule = new CustomizerEndModule();
        if (array_key_first($modules) !== $customizerEndModule->getName()) {
            array_unshift($modules, $customizerEndModule);
        }

        $variables = '';
        if (count($this->variables)) {
            foreach ($this->variables as $variable) {
                if (($description = $variable->getDescription()) !== null) {
                    $variables .= "// {$description}\n";
                }
                $variables .= "{$variable->getName()} = {$variable->getScadRepresentation()};";
                if ($specification = $variable->getSpecification()) {
                    $variables .= " {$specification}";
                }
                $variables .= "\n";
            }
        }

        $content = '';

        foreach ($this->renderables as $renderable) {
            if ($renderable instanceof HasModuleDefinitions) {
                $newModules = $renderable->getModules();
                foreach ($newModules as $newModule) {
                    $modules[$newModule->getName()] = $newModule;
                }
            }

            $content .= $this->getWrapped($renderable)->render();
        }

        $moduleContent = '';
        foreach ($modules as $module) {
            $moduleContent .= $module->getDefinition() . "\n";
        }

        $facets = '';

        if ($fa = $this->facetsConfiguration?->getMinimumFragmentAngle()) {
            $facets .= "\$fa = {$fa};\n";
        }
        if ($fs = $this->facetsConfiguration?->getMinimumFragmentSize()) {
            $facets .= "\$fs = {$fs};\n";
        }
        if ($fn = $this->facetsConfiguration?->getNumberOfFragments()) {
            $facets .= "\$fn = {$fn};\n";
        }

        $result = '';
        if ($this->showGenerator) {
            $result .= "// generated by PhpScad: https://github.com/RikudouSage/PhpScad\n\n";
        }
        $result .= $fonts;
        if ($this->configurableFacets) {
            $result .= $facets;
        }
        $result .= $variables;
        $result .= $moduleContent;
        if (!$this->configurableFacets) {
            $result .= $facets;
        }
        $result .= $content;

        return $result;
    }
}
