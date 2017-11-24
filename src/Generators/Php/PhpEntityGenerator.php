<?php

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityGeneratorConfig;
use kristijorgji\DbToPhp\Rules\Php\PhpPropertiesCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\DbToPhp\Support\TextBuffer;

class PhpEntityGenerator
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
     * @var TextBuffer
     */
    private $output;

    /**
     * @param PhpEntityGeneratorConfig $config
     * @param PhpPropertiesCollection $properties
     */
    public function __construct(
        PhpEntityGeneratorConfig $config,
        PhpPropertiesCollection $properties
    )
    {
        $this->config = $config;
        $this->properties = $properties;
        $this->output = new TextBuffer();
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
    private function addClassDeclaration()
    {
        $this->output->addLine('<?php');
        $this->output->addEmptyLines();

        $this->output->addLine(sprintf('namespace %s;', $this->config->getNamespace()));
        $this->output->addEmptyLines();

        $this->output->addLine(sprintf('class %s', $this->config->getClassName()));
        $this->output->addLine('{');
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
        $this->output->addLine(
            (new PhpSetterGenerator($property, $this->config->getPhpSetterGeneratorConfig()))->generate()
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

    /**
     * @return void
     */
    private function addClassEnding()
    {
        $this->output->addLine('}');
    }
}
