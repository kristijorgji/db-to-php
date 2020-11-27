<?php

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpGetterGeneratorConfig;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\DbToPhp\Support\TextBuffer;

class PhpGetterGenerator
{
    /**
     * @var PhpProperty
     */
    private $property;

    /**
     * @var TextBuffer
     */
    private $output;

    /**
     * @var PhpGetterGeneratorConfig
     */
    private $config;

    /**
     * @param PhpProperty $property
     * @param PhpGetterGeneratorConfig $config
     */
    public function __construct(PhpProperty $property, PhpGetterGeneratorConfig $config)
    {
        $this->property = $property;
        $this->output = new TextBuffer();
        $this->config = $config;
    }

    public function generate(): string
    {
        if ($this->config->shouldIncludeAnnotations()) {
            $this->addAnnotation();
        }
        $this->addDeclaration();
        $this->addBody();
        return $this->output->get();
    }

    private function addAnnotation()
    {
        $type  = $this->property->getType();
        $nullableText = $type->isNullable() === true ? '|null' :  '';

        $this->output->addLine('/**', 4);
        $this->output->addLine(
            sprintf('* @return %s', (string) $this->property->getType()->getType() . $nullableText),
            5
        );
        $this->output->addLine('*/', 5);
    }

    private function addDeclaration()
    {
        $type  = $this->property->getType();
        $returnType = '';
        if ($this->config->shouldTypeHint()) {
            $returnType = ': ' . ($type->isNullable() === true ? '?' : '') . (string) $type->getType();
        }

        $functionName = 'get' . ucfirst($this->property->getName());

        $this->output->addLine(
            sprintf(
                'public function %s()%s',
                $functionName,
                $returnType
            ),
            4
        );
    }

    private function addBody()
    {
        $this->output->addLine('{', 4);
        $this->output->addLine(
            sprintf('return $this->%s;', $this->property->getName()),
            8
        );
        $this->output->add('}', 4);
    }
}
