<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Rules\Php\PhpFunctionParametersCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Support\TextBuffer;
use function sprintf;

class PhpMethodAnnotationGenerator
{
    private readonly TextBuffer $output;

    public function __construct(
        private readonly PhpFunctionParametersCollection $parameters,
        private readonly ?PhpType $returnType,
    ) {
        $this->output = new TextBuffer;
    }

    public function generate(int $indentationSpaces = 4) : string
    {
        $this->output->addLine('/**', $indentationSpaces);

        foreach ($this->parameters->all() as $argument) {
            $this->output->addLine(
                sprintf('* @param %s $%s',Utils::resolveTypeForAnnotation($argument->getType()), $argument->getName()),
                $indentationSpaces + 1,
            );
        }

        if ($this->returnType !== null) {
            $returnType = Utils::resolveTypeForAnnotation($this->returnType);
        } else {
            $returnType = 'void';
        }

        $this->output->addLine(
            sprintf('* @return %s', $returnType),
            5,
        );

        $this->output->addLine('*/', $indentationSpaces + 1);

        return $this->output->get();
    }
}
