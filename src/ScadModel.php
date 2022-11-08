<?php

namespace Rikudou\PhpScad;

use JetBrains\PhpStorm\Immutable;
use Rikudou\PhpScad\FacetsConfiguration\FacetsConfiguration;
use Rikudou\PhpScad\Implementation\GetWrappedRenderable;
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

    /**
     * @var array<Module>
     */
    private readonly array $modules;

    /**
     * @param array<Renderable>         $renderables
     * @param array<Module>             $modules
     * @param array<CustomizerVariable> $variables
     */
    public function __construct(
        private readonly ?FacetsConfiguration $facetsConfiguration = null,
        private readonly Renderer $renderer = new ScadFileRenderer(),
        private readonly array $renderables = [],
        array $modules = [],
        private readonly array $variables = [],
        private readonly bool $configurableFacets = false,
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

        return new self(
            facetsConfiguration: $this->facetsConfiguration,
            renderer: $this->renderer,
            renderables: $renderables,
            modules: $this->modules,
            variables: $this->variables,
            configurableFacets: $this->configurableFacets,
        );
    }

    public function withModule(Module $module): self
    {
        $modules = $this->modules;
        $modules[$module->getName()] = $module;

        return new self(
            facetsConfiguration: $this->facetsConfiguration,
            renderer: $this->renderer,
            renderables: $this->renderables,
            modules: $modules,
            variables: $this->variables,
            configurableFacets: $this->configurableFacets,
        );
    }

    public function withFacetsConfiguration(FacetsConfiguration $configuration): self
    {
        return new self(
            facetsConfiguration: $configuration,
            renderer: $this->renderer,
            renderables: $this->renderables,
            modules: $this->modules,
            variables: $this->variables,
            configurableFacets: $this->configurableFacets,
        );
    }

    public function withVariable(CustomizerVariable $variable): self
    {
        $variables = $this->variables;
        $variables[] = $variable;

        return new self(
            facetsConfiguration: $this->facetsConfiguration,
            renderer: $this->renderer,
            renderables: $this->renderables,
            modules: $this->modules,
            variables: $variables,
            configurableFacets: $this->configurableFacets,
        );
    }

    public function render(string $outputFile): void
    {
        $this->renderer->render($outputFile, $this->getScadContent());
    }

    public function getScadContent(): string
    {
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
                $variables .= "{$variable->getName()} = {$variable->getScadRepresentation()};\n";
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
