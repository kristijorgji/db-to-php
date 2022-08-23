<?php

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Rules\Php\PhpObjectType;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Support\TextBuffer;

class PhpPropertyAnnotationGenerator
{
    /**
     * @var PhpType
     */
    private $type;

    /**
     * @var TextBuffer
     */
    private $output;

    /**
     * @param PhpType $type
     */
    public function __construct(PhpType $type)
    {
        $this->type = $type;
        $this->output = new TextBuffer();
    }

    /**
     * @param int $indentationSpaces
     * @return string
     */
    public function generate(int $indentationSpaces = 4) : string
    {
        $this->output->addLine('/**', $indentationSpaces);
        $this->output->addLine(
            sprintf(
                '* @var %s',
                Utils::resolveTypeForAnnotation($this->type)
            ),
            $indentationSpaces + 1
        );
        $this->output->addLine('*/', $indentationSpaces + 1);

        return $this->output->get();
    }
}
