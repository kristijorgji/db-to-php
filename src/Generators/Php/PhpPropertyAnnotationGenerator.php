<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Support\TextBuffer;
use function sprintf;

class PhpPropertyAnnotationGenerator
{
    private readonly TextBuffer $output;

    public function __construct(private readonly PhpType $type)
    {
        $this->output = new TextBuffer;
    }

    public function generate(int $indentationSpaces = 4) : string
    {
        $this->output->addLine('/**', $indentationSpaces);
        $this->output->addLine(
            sprintf(
                '* @var %s',
                Utils::resolveTypeForAnnotation($this->type),
            ),
            $indentationSpaces + 1,
        );
        $this->output->addLine('*/', $indentationSpaces + 1);

        return $this->output->get();
    }
}
