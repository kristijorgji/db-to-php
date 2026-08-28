<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityGeneratorConfig;
use kristijorgji\DbToPhp\Rules\Php\PhpPropertiesCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use function count;

class PhpEntityGenerator extends PhpClassGenerator
{
    public function __construct(
        private PhpEntityGeneratorConfig $config,
        private PhpPropertiesCollection $properties,
    ) {
        parent::__construct($config->getPhpClassGeneratorConfig());
    }

    public function generate() : string
    {
        $this->addClassDeclaration();
        $this->addProperties();
        if ($this->config->shouldIncludeGetters() || $this->config->shouldIncludeSetters()) {
            $this->output->addEmptyLines();
            $this->addSettersAndGetters();
        }
        $this->addClassEnding();

        return $this->output->get();
    }

    private function addProperties(): void
    {
        $propertiesCount = count($this->properties->all());

        foreach ($this->properties->all() as $i => $property) {
            $this->addProperty($property);
            if ($i < $propertiesCount - 1) {
                $this->output->addEmptyLines();
            }
        }
    }

    private function addSettersAndGetters(): void
    {
        $propertiesCount = count($this->properties->all());

        foreach ($this->properties as $i => $property) {
            if ($this->config->shouldIncludeSetters()) {
                $this->addSetter($property);
            }

            if ($this->config->shouldIncludeSetters() && $this->config->shouldIncludeGetters()) {
                $this->output->addEmptyLines();
            }

            if ($this->config->shouldIncludeGetters()) {
                $this->addGetter($property);
            }

            if ($i < $propertiesCount - 1) {
                $this->output->addEmptyLines();
            }
        }
    }

    private function addSetter(PhpProperty $property): void
    {
        $extraLines = [];
        if ($this->config->shouldTrackChanges()) {
            $extraLines[] = '$this->track(\'[%propertyName%]\', $[%propertyName%]);';
        }
        $this->output->addLine(
            (new PhpSetterGenerator(
                $property,
                $this->config->getPhpSetterGeneratorConfig(),
                $extraLines,
            ))->generate(),
        );
    }

    private function addGetter(PhpProperty $property): void
    {
        $this->output->addLine(
            (new PhpGetterGenerator($property, $this->config->getPhpGetterGeneratorConfig()))->generate(),
        );
    }

    private function addProperty(PhpProperty $property): void
    {
        $propertyGenerator = new PhpPropertyGenerator(
            $property,
            $this->config->getPhpPropertyGeneratorConfig(),
        );

        $this->output->addLine($propertyGenerator->generate());
    }
}
