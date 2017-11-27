<?php

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpClassGeneratorConfig;
use kristijorgji\DbToPhp\Support\TextBuffer;

abstract class PhpClassGenerator
{
    /**
     * @var PhpClassGeneratorConfig
     */
    private $config;

    /**
     * @var TextBuffer
     */
    protected $output;

    /**
     * @param PhpClassGeneratorConfig $config
     */
    public function __construct(PhpClassGeneratorConfig $config)
    {
        $this->config = $config;
        $this->output = new TextBuffer();
    }

    /**
     * @return void
     */
    protected function addClassDeclaration()
    {
        $this->output->addLine('<?php');
        $this->output->addEmptyLines();

        $this->output->addLine(sprintf('namespace %s;', $this->config->getNamespace()));
        $this->output->addEmptyLines();

        if ($this->config->getUses()->count() > 0) {
            foreach ($this->config->getUses()->all() as $uses) {
                $this->output->addLine(sprintf('use %s;', $uses));
            }
            $this->output->addEmptyLines();
        }

        $this->output->add(sprintf('class %s', $this->config->getClassName()));

        if ($this->config->getExtends() !== null) {
            $this->output->add(sprintf(' extends %s', $this->config->getExtends()));
        }

        $this->output->addEmptyLines();

        $this->output->addLine('{');
    }

    /**
     * @return void
     */
    protected function addClassEnding()
    {
        $this->output->addLine('}');
    }
}
