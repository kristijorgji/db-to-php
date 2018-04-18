<?php

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpSetterGeneratorConfig;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\DbToPhp\Support\TextBuffer;

class PhpSetterGenerator
{
    /**
     * @var PhpProperty
     */
    private $property;

    /**
     * @var PhpSetterGeneratorConfig
     */
    private $config;

    /**
     * @var TextBuffer
     */
    private $output;

    /**
     * @var array
     */
    private $extraLines;

    /**
     * @param PhpProperty $property
     * @param PhpSetterGeneratorConfig $config
     * @param array $extraLines
     */
    public function __construct(PhpProperty $property, PhpSetterGeneratorConfig $config, array $extraLines = [])
    {
        $this->property = $property;
        $this->config = $config;
        $this->output = new TextBuffer();
        $this->extraLines = $extraLines;
    }

    public function generate() : string
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
        $type = (string) $this->property->getType()->getType() . $nullableText;

        $this->output->addLine('/**', 4);
        $this->output->addLine(
            sprintf('* @param %s $%s', $type, $this->property->getName()),
            5
        );

        if ($this->config->isFluent()) {
            $this->output->addLine(
                sprintf('* @return %s', '$this'),
                5
            );
        }

        $this->output->addLine('*/', 5);
    }

    private function addDeclaration()
    {
        $argumentType = '';
        if ($this->config->shouldTypeHint()) {
            $type  = $this->property->getType();
            $argumentType = ($type->isNullable() === true ? '?' : '') . (string) $type->getType() . ' ';
        }

        $functionName = 'set' . ucfirst($this->property->getName());

        $this->output->addLine(
            sprintf(
                'public function %s(%s$%s)',
                $functionName,
                $argumentType,
                $this->property->getName()
            ),
            4
        );
    }

    private function addBody()
    {
        $this->output->addLine('{', 4);
        $this->output->addLine(
            sprintf('$this->%s = $%s;', $this->property->getName(), $this->property->getName()),
            8
        );

        foreach ($this->extraLines as $extraLine) {
            $this->output->addLine(
                str_replace('[%propertyName%]', $this->property->getName(), $extraLine),
                8
            );
        }

        if ($this->config->isFluent()) {
            $this->output->addLine('return $this;', 8);
        }

        $this->output->add('}', 4);
    }
}
