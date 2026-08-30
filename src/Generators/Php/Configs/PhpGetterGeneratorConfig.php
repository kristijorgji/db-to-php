<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

class PhpGetterGeneratorConfig
{
    public function __construct(private readonly bool $includeAnnotations, private readonly bool $typeHint)
    {
    }

    public function shouldIncludeAnnotations(): bool
    {
        return $this->includeAnnotations;
    }

    public function shouldTypeHint(): bool
    {
        return $this->typeHint;
    }
}
