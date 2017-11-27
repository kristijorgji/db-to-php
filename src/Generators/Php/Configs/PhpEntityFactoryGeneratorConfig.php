<?php

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

class PhpEntityFactoryGeneratorConfig
{
    /**
     * @var PhpClassGeneratorConfig
     */
    private $phpClassGeneratorConfig;

    /**
     * @var bool
     */
    private $typeHint;

    /**
     * @var bool
     */
    private $includeAnnotations;

    /**
     * @param PhpClassGeneratorConfig $phpClassGeneratorConfig
     * @param bool $typeHint
     * @param bool $includeAnnotations
     */
    public function __construct(
        PhpClassGeneratorConfig $phpClassGeneratorConfig,
        bool $typeHint,
        bool $includeAnnotations
    )
    {
        $this->phpClassGeneratorConfig = $phpClassGeneratorConfig;
        $this->typeHint = $typeHint;
        $this->includeAnnotations = $includeAnnotations;
    }

    /**
     * @return PhpClassGeneratorConfig
     */
    public function getPhpClassGeneratorConfig(): PhpClassGeneratorConfig
    {
        return $this->phpClassGeneratorConfig;
    }

    /**
     * @return bool
     */
    public function shouldTypeHint() : bool
    {
        return $this->typeHint;
    }

    /**
     * @return bool
     */
    public function shouldIncludeAnnotations() : bool
    {
        return $this->includeAnnotations;
    }
}
