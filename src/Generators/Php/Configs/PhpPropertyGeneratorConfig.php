<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

class PhpPropertyGeneratorConfig
{
    public function __construct(
        private readonly bool $includeAnnotations,
        private readonly bool $typed,
    ) {
    }

    public function shouldIncludeAnnotations(): bool
    {
        return $this->includeAnnotations;
    }

    public function shouldBeTypeHinted(): bool
    {
        return $this->typed;
    }
}
