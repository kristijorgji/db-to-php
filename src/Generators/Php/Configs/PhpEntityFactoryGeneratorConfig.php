<?php

namespace kristijorgji\DbToPhp\Generators\Php\Configs;

class PhpEntityFactoryGeneratorConfig
{
    /**
     * @var PhpClassGeneratorConfig
     */
    private $phpClassGeneratorConfig;

    /**
     * @param PhpClassGeneratorConfig $phpClassGeneratorConfig
     */
    public function __construct(PhpClassGeneratorConfig $phpClassGeneratorConfig)
    {
        $this->phpClassGeneratorConfig = $phpClassGeneratorConfig;
    }

    /**
     * @return PhpClassGeneratorConfig
     */
    public function getPhpClassGeneratorConfig(): PhpClassGeneratorConfig
    {
        return $this->phpClassGeneratorConfig;
    }
}
