<?php

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

class PhpEntityFactoryGeneratorConfig
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
     * @var bool
     */
    private $typeHint;

    /**
     * @param string $namespace
     * @param string $className
     * @param bool $typeHint
     */
    public function __construct(string $namespace, string $className, bool $typeHint)
    {
        $this->namespace = $namespace;
        $this->className = $className;
        $this->typeHint = $typeHint;
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
     * @return boolean
     */
    public function isTypeHint(): bool
    {
        return $this->typeHint;
    }
}
