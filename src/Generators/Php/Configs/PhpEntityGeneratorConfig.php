<?php

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

class PhpEntityGeneratorConfig
{
    /**
     * @var PhpClassGeneratorConfig
     */
    private $phpClassGeneratorConfig;

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
     * @var bool
     */
    private $shouldTrackChanges;

    /**
     * @param PhpClassGeneratorConfig $phpClassGeneratorConfig
     * @param bool $includeSetters
     * @param bool $includeGetters
     * @param PhpSetterGeneratorConfig $phpSetterGeneratorConfig
     * @param PhpGetterGeneratorConfig $phpGetterGeneratorConfig
     * @param PhpPropertyGeneratorConfig $phpPropertyGeneratorConfig
     * @param bool $shouldTrackChanges
     */
    public function __construct(
        PhpClassGeneratorConfig $phpClassGeneratorConfig,
        bool $includeSetters,
        bool $includeGetters,
        PhpSetterGeneratorConfig $phpSetterGeneratorConfig,
        PhpGetterGeneratorConfig $phpGetterGeneratorConfig,
        PhpPropertyGeneratorConfig $phpPropertyGeneratorConfig,
        bool $shouldTrackChanges
    ) {
        $this->phpClassGeneratorConfig = $phpClassGeneratorConfig;
        $this->includeSetters = $includeSetters;
        $this->includeGetters = $includeGetters;
        $this->phpSetterGeneratorConfig = $phpSetterGeneratorConfig;
        $this->phpGetterGeneratorConfig = $phpGetterGeneratorConfig;
        $this->phpPropertyGeneratorConfig = $phpPropertyGeneratorConfig;
        $this->shouldTrackChanges = $shouldTrackChanges;
    }

    /**
     * @return PhpClassGeneratorConfig
     */
    public function getPhpClassGeneratorConfig(): PhpClassGeneratorConfig
    {
        return $this->phpClassGeneratorConfig;
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

    /**
     * @return bool
     */
    public function shouldTrackChanges() : bool
    {
        return $this->shouldTrackChanges;
    }
}
