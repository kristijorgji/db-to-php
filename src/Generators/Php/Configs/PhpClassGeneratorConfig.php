<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

use kristijorgji\DbToPhp\Support\StringCollection;

class PhpClassGeneratorConfig
{
    public function __construct(
        private readonly string $namespace,
        private readonly string $className,
        private readonly StringCollection $uses,
        private readonly ?string $extends = null,
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
