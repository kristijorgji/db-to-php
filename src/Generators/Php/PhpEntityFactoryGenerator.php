<?php

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityFactoryGeneratorConfig;
use kristijorgji\DbToPhp\Rules\Php\PhpFunctionParameter;
use kristijorgji\DbToPhp\Rules\Php\PhpFunctionParametersCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpObjectType;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;

class PhpEntityFactoryGenerator extends PhpClassGenerator
{
    /**
     * @var PhpEntityFactoryGeneratorConfig
     */
    private $config;

    /**
     * @var PhpEntityFactoryFieldsCollection
     */
    private $fieldsInfo;

    /**
     * @var string
     */
    private $entityClassName;

    /**
     * @param PhpEntityFactoryGeneratorConfig $config
     * @param PhpEntityFactoryFieldsCollection $fieldsInfo
     * @param string $entityClassName
     */
    public function __construct(
        PhpEntityFactoryGeneratorConfig $config,
        PhpEntityFactoryFieldsCollection $fieldsInfo,
        string $entityClassName
    )
    {
        parent::__construct($config->getPhpClassGeneratorConfig());
        $this->config = $config;
        $this->fieldsInfo = $fieldsInfo;
        $this->entityClassName = $entityClassName;
    }

    /**
     * @return string
     */
    public function generate() : string
    {
        $this->addClassDeclaration();
        $this->addMakeFunction();
        $this->output->addEmptyLines();
        $this->addMakeFromDataFunction();
        $this->output->addEmptyLines();
        $this->addMakeDataFunction();
        $this-> addClassEnding();
        return $this->output->get();
    }

    /**
     * @return void
     */
    private function addMakeFunction()
    {
        if ($this->config->shouldIncludeAnnotations()) {
            $this->addMakeFunctionAnnotations();
        }

        $returnType = '';

        if ($this->config->shouldTypeHint()) {
            $returnType .= sprintf(' : %s', $this->entityClassName);
        }

        $this->output->addLine('public static function make(array $data = [])' . $returnType, 4);
        $this->output->addLine('{', 4);

        $this->output->addLine('return self::makeFromData(self::makeData($data));', 8);

        $this->output->addLine('}', 4);
    }

    /**
     * @return void
     */
    private function addMakeFunctionAnnotations()
    {
        $arrayType = new PhpType(new PhpTypes(new PhpTypes(PhpTypes::ARRAY)), false);
        $returnType = new PhpObjectType(
            new PhpTypes(new PhpTypes(PhpTypes::OBJECT)),
            false, $this->entityClassName
        );

        $methodAnnotationGenerator = new PhpMethodAnnotationGenerator(
            new PhpFunctionParametersCollection(... [
                new PhpFunctionParameter('data', $arrayType)
            ]),
            $returnType,
            $this->config->shouldTypeHint()
        );

        $this->output->add($methodAnnotationGenerator->generate());
    }

    /**
     * @return void
     */
    private function addMakeFromDataFunction()
    {
        if ($this->config->shouldIncludeAnnotations()) {
            $this->addMakeFromDataFunctionAnnotations();
        }

        $returnType = '';

        if ($this->config->shouldTypeHint()) {
            $returnType .= sprintf(' : %s', $this->entityClassName);
        }

        $this->output->addLine('public static function makeFromData(array $data)' . $returnType, 4);
        $this->output->addLine('{', 4);

        $this->output->addLine(
            sprintf('return self::mapArrayToEntity($data, %s::class);', $this->entityClassName),
            8
        );

        $this->output->addLine('}', 4);
    }

    /**
     * @return void
     */
    private function addMakeFromDataFunctionAnnotations()
    {
        $this->addMakeFunctionAnnotations();
    }

    /**
     * @return void
     */
    private function addMakeDataFunction()
    {
        if ($this->config->shouldIncludeAnnotations()) {
           $this->addMakeDataFunctionAnnotations();
        }

        $returnType = '';

        if ($this->config->shouldTypeHint()) {
            $returnType .= ' : array';
        }

        $this->output->addLine('public static function makeData(array $data = [])' . $returnType, 4);
        $this->output->addLine('{', 4);

        $this->output->addLine('return [', 8);

        foreach ($this->fieldsInfo->all() as $fieldInfo) {
            $quotedDbFieldName = sprintf('\'%s\'', $fieldInfo->getDbFieldName());
            $this->output->addLine(
                sprintf(
                    '%s => $data[%s] ?? %s,',
                    $quotedDbFieldName,
                    $quotedDbFieldName,
                    $fieldInfo->getResolvingCall()
                ),
                12
            );
        }

        $this->output->addLine('];', 8);
        $this->output->addLine('}', 4);
    }

    /**
     * @return void
     */
    private function addMakeDataFunctionAnnotations()
    {
        $arrayType = new PhpType(new PhpTypes(new PhpTypes(PhpTypes::ARRAY)), false);
        $methodAnnotationGenerator = new PhpMethodAnnotationGenerator(
            new PhpFunctionParametersCollection(... [
                new PhpFunctionParameter('data', $arrayType)
            ]),
            $arrayType,
            $this->config->shouldTypeHint()
        );

        $this->output->add($methodAnnotationGenerator->generate());
    }
}
