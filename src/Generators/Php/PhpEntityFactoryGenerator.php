<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Generators\Php;

use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityFactoryGeneratorConfig;
use kristijorgji\DbToPhp\Rules\Php\PhpFunctionParameter;
use kristijorgji\DbToPhp\Rules\Php\PhpFunctionParametersCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpObjectType;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;
use function sprintf;

class PhpEntityFactoryGenerator extends PhpClassGenerator
{
    public function __construct(
        private PhpEntityFactoryGeneratorConfig $config,
        private PhpEntityFactoryFieldsCollection $fieldsInfo,
        private string $entityClassName,
    ) {
        parent::__construct($config->getPhpClassGeneratorConfig());
    }

    public function generate() : string
    {
        $this->addClassDeclaration();
        $this->addFieldsProperty();
        $this->addMakeFunction();
        $this->output->addEmptyLines();
        $this->addMakeFromDataFunction();
        $this->output->addEmptyLines();
        $this->addMakeDataFunction();
        $this-> addClassEnding();
        return $this->output->get();
    }

    private function addFieldsProperty(): void
    {
        if ($this->config->shouldIncludeAnnotations()) {
            $propertyAnnotationGenerator = new PhpPropertyAnnotationGenerator(
                new PhpType(PhpTypes::ARRAY, false),
            );
            $this->output->add($propertyAnnotationGenerator->generate());
        }

        $this->output->addLine('protected static $fields = [', 4);
        foreach ($this->fieldsInfo->all() as $fieldInfo) {
            $quotedDbFieldName = sprintf('\'%s\'', $fieldInfo->getDbFieldName());
            $this->output->addLine($quotedDbFieldName . ',', 8);
        }
        $this->output->addLine('];', 4);
        $this->output->addEmptyLines();
    }

    private function addMakeFunction(): void
    {
        if ($this->config->shouldIncludeAnnotations()) {
            $this->addMakeFunctionAnnotations();
        }

        $returnType = '';

        if ($this->config->shouldTypeHint()) {
            $returnType .= sprintf(': %s', $this->entityClassName);
        }

        $this->output->addLine('public static function make(array $data = [])' . $returnType, 4);
        $this->output->addLine('{', 4);

        $this->output->addLine('return self::makeFromData(self::makeData($data));', 8);

        $this->output->addLine('}', 4);
    }

    private function addMakeFunctionAnnotations(): void
    {
        $arrayType = new PhpType(PhpTypes::ARRAY, false);
        $returnType = new PhpObjectType(false, $this->entityClassName);

        $methodAnnotationGenerator = new PhpMethodAnnotationGenerator(
            new PhpFunctionParametersCollection(... [
                new PhpFunctionParameter('data', $arrayType),
            ]),
            $returnType,
            $this->config->shouldTypeHint(),
        );

        $this->output->add($methodAnnotationGenerator->generate());
    }

    private function addMakeFromDataFunction(): void
    {
        if ($this->config->shouldIncludeAnnotations()) {
            $this->addMakeFromDataFunctionAnnotations();
        }

        $returnType = '';

        if ($this->config->shouldTypeHint()) {
            $returnType .= sprintf(': %s', $this->entityClassName);
        }

        $this->output->addLine('public static function makeFromData(array $data)' . $returnType, 4);
        $this->output->addLine('{', 4);

        $this->output->addLine('self::validateData($data);', 8);
        $this->output->addLine(
            sprintf('return self::mapArrayToEntity($data, %s::class);', $this->entityClassName),
            8,
        );

        $this->output->addLine('}', 4);
    }

    private function addMakeFromDataFunctionAnnotations(): void
    {
        $this->addMakeFunctionAnnotations();
    }

    private function addMakeDataFunction(): void
    {
        if ($this->config->shouldIncludeAnnotations()) {
           $this->addMakeDataFunctionAnnotations();
        }

        $returnType = '';

        if ($this->config->shouldTypeHint()) {
            $returnType .= ': array';
        }

        $this->output->addLine('public static function makeData(array $data = [])' . $returnType, 4);
        $this->output->addLine('{', 4);

        $this->output->addLine('self::validateData($data);', 8);
        $this->output->addLine('return [', 8);

        foreach ($this->fieldsInfo->all() as $fieldInfo) {
            $quotedDbFieldName = sprintf('\'%s\'', $fieldInfo->getDbFieldName());
            $this->output->addLine(
                sprintf(
                    '%s => array_key_exists(%s, $data) ?',
                    $quotedDbFieldName,
                    $quotedDbFieldName,
                ),
                12,
            );

            $this->output->addLine(
                sprintf(
                    '$data[%s] : %s,',
                    $quotedDbFieldName,
                    $fieldInfo->getResolvingCall(),
                ),
                16,
            );
        }

        $this->output->addLine('];', 8);
        $this->output->addLine('}', 4);
    }

    private function addMakeDataFunctionAnnotations(): void
    {
        $arrayType = new PhpType(PhpTypes::ARRAY, false);
        $methodAnnotationGenerator = new PhpMethodAnnotationGenerator(
            new PhpFunctionParametersCollection(... [
                new PhpFunctionParameter('data', $arrayType),
            ]),
            $arrayType,
            $this->config->shouldTypeHint(),
        );

        $this->output->add($methodAnnotationGenerator->generate());
    }
}
