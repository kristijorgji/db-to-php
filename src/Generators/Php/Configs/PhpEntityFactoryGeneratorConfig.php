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
     * @param PhpClassGeneratorConfig $phpClassGeneratorConfig
     * @param bool $typeHint
     */
    public function __construct(
        PhpClassGeneratorConfig $phpClassGeneratorConfig,
        bool $typeHint
    )
    {
        $this->phpClassGeneratorConfig = $phpClassGeneratorConfig;
        $this->typeHint = $typeHint;
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
    public function isTypeHint(): bool
    {
        return $this->typeHint;
    }
}
