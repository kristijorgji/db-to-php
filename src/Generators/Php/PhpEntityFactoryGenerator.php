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
     * @var FieldsCollection
     */
    private $fields;

    /**
     * @var string
     */
    private $entityClassName;

    /**
     * @param PhpEntityFactoryGeneratorConfig $config
     * @param FieldsCollection $fields
     * @param string $entityClassName
     */
    public function __construct(
        PhpEntityFactoryGeneratorConfig $config,
        FieldsCollection $fields,
        string $entityClassName
    )
    {
        parent::__construct($config->getPhpClassGeneratorConfig());
        $this->config = $config;
        $this->fields = $fields;
        $this->entityClassName = $entityClassName;
    }

    public function generate() : string
    {
        $this->addClassDeclaration();
        return $this->output->get();
    }
}
