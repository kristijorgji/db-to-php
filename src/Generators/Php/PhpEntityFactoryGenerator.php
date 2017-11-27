<?php

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Db\Fields\FieldsCollection;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityFactoryGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Resolvers\PhpEntityFactoryFieldFunctionResolver;
use kristijorgji\DbToPhp\Rules\Php\PhpFunctionParameter;
use kristijorgji\DbToPhp\Rules\Php\PhpFunctionParametersCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpPropertiesCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;
use kristijorgji\DbToPhp\Support\TextBuffer;

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
     * @var PhpEntityFactoryFieldFunctionResolver
     */
    private $entityFactoryFieldFunctionResolver;

    /**
     * @var string
     */
    private $entityClassName;

    /**
     * @param PhpEntityFactoryGeneratorConfig $config
     * @param PhpEntityFactoryFieldsCollection $fieldsInfo
     * @param PhpEntityFactoryFieldFunctionResolver $entityFactoryFieldFunctionResolver
     * @param string $entityClassName
     */
    public function __construct(
        PhpEntityFactoryGeneratorConfig $config,
        PhpEntityFactoryFieldsCollection $fieldsInfo,
        PhpEntityFactoryFieldFunctionResolver $entityFactoryFieldFunctionResolver,
        string $entityClassName
    )
    {
        parent::__construct($config->getPhpClassGeneratorConfig());
        $this->config = $config;
        $this->fieldsInfo = $fieldsInfo;
        $this->entityFactoryFieldFunctionResolver = $entityFactoryFieldFunctionResolver;
        $this->entityClassName = $entityClassName;
    }

    public function generate() : string
    {
        $this->addClassDeclaration();
        $this->addMakeDataFunction();
        $this-> addClassEnding();
        return $this->output->get();
    }

    private function addMakeDataFunction()
    {
        if ($this->config->shouldIncludeAnnotations()) {
           $this->addMakeDataFunctionAnnotations();
        }

        $returnType = '';

        if ($this->config->shouldTypeHint()) {
            $returnType .= ' : array';
        }

        $this->output->addLine('public static function makeData(array $customData = [])' . $returnType, 4);
        $this->output->addLine('{', 4);

        $this->output->addLine('return [', 8);

        foreach ($this->fieldsInfo->all() as $fieldInfo) {
            $quotedDbFieldName = sprintf('\'%s\'', $fieldInfo->getDbFieldName());
            $this->output->addLine(
                sprintf(
                    '%s => $customData[%s] ?? %s,',
                    $quotedDbFieldName,
                    $quotedDbFieldName,
                    $this->entityFactoryFieldFunctionResolver->resolve(
                        $fieldInfo->getType(),
                        $fieldInfo->getLengthLimit(),
                        $fieldInfo->isSigned()
                    )
                ),
                12
            );
        }

        $this->output->addLine('];', 8);
        $this->output->addLine('}', 4);
    }

    private function addMakeDataFunctionAnnotations()
    {
        $arrayType = new PhpType(new PhpTypes(new PhpTypes(PhpTypes::ARRAY)), false);
        $methodAnnotationGenerator = new PhpMethodAnnotationGenerator(
            new PhpFunctionParametersCollection(... [
                new PhpFunctionParameter('customData', $arrayType)
            ]),
            $arrayType,
            $this->config->shouldTypeHint()
        );

        $this->output->add($methodAnnotationGenerator->generate());

    }
}
