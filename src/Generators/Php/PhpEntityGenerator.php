<?php

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityGeneratorConfig;
use kristijorgji\DbToPhp\Rules\Php\PhpPropertiesCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;

class PhpEntityGenerator extends PhpClassGenerator
{
    /**
     * @var PhpEntityGeneratorConfig
     */
    private $config;

    /**
     * @var PhpPropertiesCollection[]
     */
    private $properties;

    /**
     * @param PhpEntityGeneratorConfig $config
     * @param PhpPropertiesCollection $properties
     */
    public function __construct(
        PhpEntityGeneratorConfig $config,
        PhpPropertiesCollection $properties
    )
    {
        parent::__construct($config->getPhpClassGeneratorConfig());
        $this->config = $config;
        $this->properties = $properties;
    }

    /**
     * @return string
     */
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

    /**
     * @return void
     */
    private function addProperties()
    {
        $propertiesCount = count($this->properties->all());

        foreach ($this->properties->all() as $i => $property) {
            $this->addProperty($property);
            if ($i < $propertiesCount - 1) {
                $this->output->addEmptyLines();
            }
        }
    }

    /**
     * @return void
     */
    private function addSettersAndGetters()
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

    /**
     * @param PhpProperty $property
     */
    private function addSetter(PhpProperty $property)
    {
        $extraLines = [];
        if ($this->config->shouldTrackChanges()) {
            $extraLines[] = '$this->track(\'[%propertyName%]\', $[%propertyName%]);';
        }
        $this->output->addLine(
            (new PhpSetterGenerator(
                $property,
                $this->config->getPhpSetterGeneratorConfig(),
                $extraLines
            ))->generate()
        );
    }

    /**
     * @param PhpProperty $property
     */
    private function addGetter(PhpProperty $property)
    {
        $this->output->addLine(
            (new PhpGetterGenerator($property, $this->config->getPhpGetterGeneratorConfig()))->generate()
        );
    }

    /**
     * @param PhpProperty $property
     */
    private function addProperty(PhpProperty $property)
    {
        $propertyGenerator = new PhpPropertyGenerator(
            $property,
            $this->config->getPhpPropertyGeneratorConfig()
        );

        $this->output->addLine($propertyGenerator->generate());
    }
}
