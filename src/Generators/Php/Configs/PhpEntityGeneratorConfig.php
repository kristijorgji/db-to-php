<?php

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

class PhpEntityGeneratorConfig
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
    private $includeSetters;

    /**
     * @var bool
     */
    private $includeGetters;

    /**
     * @var PhpSetterGeneratorConfig
     */
    private $phpSetterGeneratorConfig;

    /**
     * @var PhpGetterGeneratorConfig
     */
    private $phpGetterGeneratorConfig;

    /**
     * @var PhpPropertyGeneratorConfig
     */
    private $phpPropertyGeneratorConfig;

    /**
     * @param string $namespace
     * @param string $className
     * @param bool $includeSetters
     * @param bool $includeGetters
     * @param PhpSetterGeneratorConfig $phpSetterGeneratorConfig
     * @param PhpGetterGeneratorConfig $phpGetterGeneratorConfig
     * @param PhpPropertyGeneratorConfig $phpPropertyGeneratorConfig
     */
    public function __construct(
        string $namespace,
        string $className,
        bool $includeSetters,
        bool $includeGetters,
        PhpSetterGeneratorConfig $phpSetterGeneratorConfig,
        PhpGetterGeneratorConfig $phpGetterGeneratorConfig,
        PhpPropertyGeneratorConfig $phpPropertyGeneratorConfig
    ) {

        $this->namespace = $namespace;
        $this->className = $className;
        $this->includeSetters = $includeSetters;
        $this->includeGetters = $includeGetters;
        $this->phpSetterGeneratorConfig = $phpSetterGeneratorConfig;
        $this->phpGetterGeneratorConfig = $phpGetterGeneratorConfig;
        $this->phpPropertyGeneratorConfig = $phpPropertyGeneratorConfig;
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
    public function shouldIncludeSetters(): bool
    {
        return $this->includeSetters;
    }

    /**
     * @return boolean
     */
    public function shouldIncludeGetters(): bool
    {
        return $this->includeGetters;
    }

    /**
     * @return PhpSetterGeneratorConfig
     */
    public function getPhpSetterGeneratorConfig(): PhpSetterGeneratorConfig
    {
        return $this->phpSetterGeneratorConfig;
    }

    /**
     * @return PhpGetterGeneratorConfig
     */
    public function getPhpGetterGeneratorConfig(): PhpGetterGeneratorConfig
    {
        return $this->phpGetterGeneratorConfig;
    }

    /**
     * @return PhpPropertyGeneratorConfig
     */
    public function getPhpPropertyGeneratorConfig(): PhpPropertyGeneratorConfig
    {
        return $this->phpPropertyGeneratorConfig;
    }
}
