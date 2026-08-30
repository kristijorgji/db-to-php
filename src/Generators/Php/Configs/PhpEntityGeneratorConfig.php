<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

class PhpEntityGeneratorConfig
{
    public function __construct(
        private readonly PhpClassGeneratorConfig $phpClassGeneratorConfig,
        private readonly bool $includeSetters,
        private readonly bool $includeGetters,
        private readonly PhpSetterGeneratorConfig $phpSetterGeneratorConfig,
        private readonly PhpGetterGeneratorConfig $phpGetterGeneratorConfig,
        private readonly PhpPropertyGeneratorConfig $phpPropertyGeneratorConfig,
        private readonly bool $shouldTrackChanges,
    ) {
    }

    public function getPhpClassGeneratorConfig(): PhpClassGeneratorConfig
    {
        return $this->phpClassGeneratorConfig;
    }

    public function shouldIncludeSetters(): bool
    {
        return $this->includeSetters;
    }

    public function shouldIncludeGetters(): bool
    {
        return $this->includeGetters;
    }

    public function getPhpSetterGeneratorConfig(): PhpSetterGeneratorConfig
    {
        return $this->phpSetterGeneratorConfig;
    }

    public function getPhpGetterGeneratorConfig(): PhpGetterGeneratorConfig
    {
        return $this->phpGetterGeneratorConfig;
    }

    public function getPhpPropertyGeneratorConfig(): PhpPropertyGeneratorConfig
    {
        return $this->phpPropertyGeneratorConfig;
    }

    public function shouldTrackChanges() : bool
    {
        return $this->shouldTrackChanges;
    }
}
