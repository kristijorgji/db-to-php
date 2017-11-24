<?php

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Db\FieldsCollection;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityFactoryGeneratorConfig;
use kristijorgji\DbToPhp\Support\TextBuffer;

class PhpEntityFactoryGenerator
{
    /**
     * @var PhpEntityFactoryGeneratorConfig
     */
    private $config;

    /**
     * @var TextBuffer
     */
    private $output;

    /**
     * @param PhpEntityFactoryGeneratorConfig $config
     */
    public function __construct(
        PhpEntityFactoryGeneratorConfig $config
    )
    {
        $this->config = $config;
        $this->output = new TextBuffer();
    }

    public function generate() : string
    {
        $this->addClassDeclaration();
    }

    /**
     * @return void
     */
    private function addClassDeclaration()
    {
        $this->output->addLine('<?php');
        $this->output->addEmptyLines();

        $this->output->addLine(sprintf('namespace %s;', $this->config->getNamespace()));
        $this->output->addEmptyLines();

        $this->output->addLine(sprintf('class %s', $this->config->getClassName()));
        $this->output->addLine('{');
    }
}
