<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

use kristijorgji\DbToPhp\Support\StringCollection;

class PhpClassGeneratorConfig
{
    public function __construct(
        private string $namespace,
        private string $className,
        private StringCollection $uses,
        private ?string $extends = null,
    ) {
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getUses(): StringCollection
    {
        return $this->uses;
    }

    public function getExtends(): ?string
    {
        return $this->extends;
    }
}
