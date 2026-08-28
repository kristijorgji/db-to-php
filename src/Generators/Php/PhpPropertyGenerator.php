<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpPropertyGeneratorConfig;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\DbToPhp\Support\TextBuffer;
use function sprintf;

class PhpPropertyGenerator
{
    private TextBuffer $output;

    public function __construct(private PhpProperty $property, private PhpPropertyGeneratorConfig $config)
    {
        $this->output = new TextBuffer;
    }

    public function generate() : string
    {
        if ($this->config->shouldIncludeAnnotations()) {
            $this->addAnnotation();
        }

        $this->addDeclaration();

        return $this->output->get();
    }

    private function addAnnotation(): void
    {
        $propertyAnnotationGenerator = new PhpPropertyAnnotationGenerator(
            $this->property->getType(),
        );

        $this->output->add(
            $propertyAnnotationGenerator->generate(),
        );
    }

    private function addDeclaration(): void
    {
        if ($this->config->shouldBeTypeHinted()) {
            $this->output->add(
                sprintf(
                    '%s %s $%s;',
                    $this->property->getAccessModifier()->value,
                    Utils::resolveType($this->property->getType()),
                    $this->property->getName(),
                ),
                4,
            );
        } else {
            $this->output->add(
                sprintf(
                    '%s $%s;',
                    $this->property->getAccessModifier()->value,
                    $this->property->getName(),
                ),
                4,
            );
        }
    }
}
