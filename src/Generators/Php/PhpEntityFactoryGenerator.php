<?php

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Db\FieldsCollection;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityFactoryGeneratorConfig;
use kristijorgji\DbToPhp\Support\TextBuffer;

class PhpEntityFactoryGenerator extends PhpClassGenerator
{
    /**
     * @var PhpEntityFactoryGeneratorConfig
     */
    private $config;

    /**
     * @param PhpEntityFactoryGeneratorConfig $config
     */
    public function __construct(
        PhpEntityFactoryGeneratorConfig $config
    )
    {
        parent::__construct($config->getPhpClassGeneratorConfig());
        $this->config = $config;
    }

    public function generate() : string
    {
        $this->addClassDeclaration();
    }
}
