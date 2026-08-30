<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpGetterGeneratorConfig;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\DbToPhp\Support\TextBuffer;
use function sprintf;
use function ucfirst;

class PhpGetterGenerator
{
    private readonly TextBuffer $output;

    public function __construct(
        private readonly PhpProperty $property,
        private readonly PhpGetterGeneratorConfig $config,
    ) {
        $this->output = new TextBuffer;
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

    private function addAnnotation(): void
    {
        $type  = $this->property->getType();
        $nullableText = $type->isNullable() ? '|null' :  '';

        $this->output->addLine('/**', 4);
        $this->output->addLine(
            sprintf('* @return %s', $this->property->getType()->getType()->value . $nullableText),
            5,
        );
        $this->output->addLine('*/', 5);
    }

    private function addDeclaration(): void
    {
        $type  = $this->property->getType();
        $returnType = '';
        if ($this->config->shouldTypeHint()) {
            $returnType = ': ' . ($type->isNullable() ? '?' : '') . $type->getType()->value;
        }

        $functionName = 'get' . ucfirst($this->property->getName());

        $this->output->addLine(
            sprintf(
                'public function %s()%s',
                $functionName,
                $returnType,
            ),
            4,
        );
    }

    private function addBody(): void
    {
        $this->output->addLine('{', 4);
        $this->output->addLine(
            sprintf('return $this->%s;', $this->property->getName()),
            8,
        );
        $this->output->add('}', 4);
    }
}
