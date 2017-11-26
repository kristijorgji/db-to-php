<?php

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

use kristijorgji\DbToPhp\Support\StringCollection;

class PhpClassGeneratorConfig
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $className;

    /**
     * @var StringCollection
     */
    private $uses;

    /**
     * @var ?string
     */
    private $extends;

    /**
     * @param string $namespace
     * @param string $className
     * @param StringCollection $uses
     * @param string|null $extends
     */
    public function __construct($namespace, $className, StringCollection $uses, ?string $extends)
    {
        $this->namespace = $namespace;
        $this->className = $className;
        $this->uses = $uses;
        $this->extends = $extends;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return StringCollection
     */
    public function getUses(): StringCollection
    {
        return $this->uses;
    }

    /**
     * @return string|null
     */
    public function getExtends(): ?string
    {
        return $this->extends;
    }
}