<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpSetterGeneratorConfig;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\DbToPhp\Support\TextBuffer;
use function sprintf;
use function str_replace;
use function ucfirst;

class PhpSetterGenerator
{
    private readonly TextBuffer $output;

    public function __construct(
        private readonly PhpProperty $property,
        private readonly PhpSetterGeneratorConfig $config,
        private readonly array $extraLines = [],
    ) {
        $this->output = new TextBuffer;
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

    private function addAnnotation(): void
    {
        $type  = $this->property->getType();
        $nullableText = $type->isNullable() ? '|null' :  '';
        $type = $this->property->getType()->getType()->value . $nullableText;

        $this->output->addLine('/**', 4);
        $this->output->addLine(
            sprintf('* @param %s $%s', $type, $this->property->getName()),
            5,
        );

        if ($this->config->isFluent()) {
            $this->output->addLine(
                sprintf('* @return %s', '$this'),
                5,
            );
        }

        $this->output->addLine('*/', 5);
    }

    private function addDeclaration(): void
    {
        $argumentType = '';
        if ($this->config->shouldTypeHint()) {
            $type  = $this->property->getType();
            $argumentType = ($type->isNullable() ? '?' : '') . $type->getType()->value . ' ';
        }

        $functionName = 'set' . ucfirst($this->property->getName());

        $this->output->addLine(
            sprintf(
                'public function %s(%s$%s)',
                $functionName,
                $argumentType,
                $this->property->getName(),
            ),
            4,
        );
    }

    private function addBody(): void
    {
        $this->output->addLine('{', 4);
        $this->output->addLine(
            sprintf('$this->%s = $%s;', $this->property->getName(), $this->property->getName()),
            8,
        );

        foreach ($this->extraLines as $extraLine) {
            $this->output->addLine(
                str_replace('[%propertyName%]', $this->property->getName(), $extraLine),
                8,
            );
        }

        if ($this->config->isFluent()) {
            $this->output->addLine('return $this;', 8);
        }

        $this->output->add('}', 4);
    }
}
