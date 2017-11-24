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
        $type  = $this->property->getType();
        $nullableText = $type->isNullable() === true ? '|null' :  '';

        $this->output->addLine('/**', 4);
        $this->output->addLine(
            sprintf('* @var %s', (string) $this->property->getType()->getType() . $nullableText),
            5
        );
        $this->output->addLine('*/', 5);
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
