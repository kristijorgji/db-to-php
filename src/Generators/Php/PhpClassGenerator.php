<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpClassGeneratorConfig;
use kristijorgji\DbToPhp\Support\TextBuffer;
use function sprintf;

abstract class PhpClassGenerator
{
    protected TextBuffer $output;

    public function __construct(private PhpClassGeneratorConfig $config)
    {
        $this->output = new TextBuffer;
    }

    protected function addClassDeclaration(): void
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

    protected function addClassEnding(): void
    {
        $this->output->addLine('}');
    }
}
