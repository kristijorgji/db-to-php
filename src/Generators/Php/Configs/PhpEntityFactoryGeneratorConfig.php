<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

class PhpEntityFactoryGeneratorConfig
{
    public function __construct(
        private PhpClassGeneratorConfig $phpClassGeneratorConfig,
        private bool $typeHint,
        private bool $includeAnnotations,
    ) {
    }

    public function getPhpClassGeneratorConfig(): PhpClassGeneratorConfig
    {
        return $this->phpClassGeneratorConfig;
    }

    public function shouldTypeHint() : bool
    {
        return $this->typeHint;
    }

    public function shouldIncludeAnnotations() : bool
    {
        return $this->includeAnnotations;
    }
}
