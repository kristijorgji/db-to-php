<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

class PhpSetterGeneratorConfig
{
    public function __construct(private bool $includeAnnotations, private bool $typeHint, private bool $isFluent)
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

    public function isFluent(): bool
    {
        return $this->isFluent;
    }
}
