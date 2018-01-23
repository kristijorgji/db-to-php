<?php

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpPropertyGeneratorConfig;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\DbToPhp\Support\TextBuffer;

class PhpPropertyGenerator
{
    /**
     * @var PhpProperty
     */
    private $property;

    /**
     * @var PhpPropertyGeneratorConfig
     */
    private $config;

    /**
     * @var TextBuffer
     */
    private $output;

    /**
     * @param PhpProperty $property
     * @param PhpPropertyGeneratorConfig $config
     */
    public function __construct(PhpProperty $property, PhpPropertyGeneratorConfig $config)
    {
        $this->property = $property;
        $this->config = $config;
        $this->output = new TextBuffer();
    }

    public function generate() : string
    {
        if ($this->config->shouldIncludeAnnotations()) {
            $this->addAnnotation();
        }

        $this->addDeclaration();

        return $this->output->get();
    }

    private function addAnnotation()
    {
        $propertyAnnotationGenerator = new PhpPropertyAnnotationGenerator(
            $this->property->getType()
        );

        $this->output->add(
            $propertyAnnotationGenerator->generate()
        );
    }

    private function addDeclaration()
    {
        $this->output->add(
            sprintf(
                '%s $%s;',
                (string) $this->property->getAccessModifier(),
                $this->property->getName()
            ),
            4
        );
    }
}
