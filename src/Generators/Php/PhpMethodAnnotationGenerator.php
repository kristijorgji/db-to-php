<?php

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Rules\Php\PhpFunctionParametersCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpObjectType;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Support\TextBuffer;

class PhpMethodAnnotationGenerator
{
    /**
     * @var PhpFunctionParametersCollection
     */
    private $parameters;

    /**
     * @var ?PhpType
     */
    private $returnType;

    /**
     * @var bool
     */
    private $typeHint;

    /**
     * @var TextBuffer
     */
    private $output;

    /**
     * @param PhpFunctionParametersCollection $parameters
     * @param $returnType
     * @param bool $typeHint
     */
    public function __construct(PhpFunctionParametersCollection $parameters, PhpType $returnType, bool $typeHint)
    {
        $this->parameters = $parameters;
        $this->returnType = $returnType;
        $this->typeHint = $typeHint;
        $this->output = new TextBuffer();
    }

    /**
     * @param int $indentationSpaces
     * @return string
     */
    public function generate(int $indentationSpaces = 4) : string
    {
        $this->output->addLine('/**', $indentationSpaces);

        foreach ($this->parameters->all() as $argument) {
            $this->output->addLine(
                sprintf('* @param %s $%s', $this->resolveType($argument->getType()), $argument->getName()),
                $indentationSpaces + 1
            );
        }

        if ($this->returnType !== null) {
            $returnType = $this->resolveType($this->returnType);
        } else {
            $returnType = 'void';
        }

        $this->output->addLine(
            sprintf('* @return %s', $returnType),
            5
        );

        $this->output->addLine('*/', $indentationSpaces + 1);

        return $this->output->get();
    }

    /**
     * @param PhpType $type
     * @return string
     */
    private function resolveType(PhpType $type) : string
    {
        $nullableText = $type->isNullable() === true ? '|null' :  '';
        if ($type instanceof PhpObjectType) {
            return (string) $type->getClassName() . $nullableText;
        }

        return (string) $type->getType() . $nullableText;
    }
}