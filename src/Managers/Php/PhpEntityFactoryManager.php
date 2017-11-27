<?php

namespace kristijorgji\DbToPhp\Managers\Php;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\Db\Fields\BinaryField;
use kristijorgji\DbToPhp\Db\Fields\FieldsCollection;
use kristijorgji\DbToPhp\Db\Fields\IntegerField;
use kristijorgji\DbToPhp\Db\Fields\TextField;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpClassGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityFactoryGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryField;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryFieldsCollection;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryGenerator;
use kristijorgji\DbToPhp\Generators\Resolvers\PhpEntityFactoryFieldFunctionResolver;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperInterface;
use kristijorgji\DbToPhp\Support\StringCollection;

class PhpEntityFactoryManager extends AbstractPhpManager
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var PhpEntityManager
     */
    private $entityManager;

    /**
     * @param DatabaseAdapterInterface $databaseAdapter
     * @param PhpTypeMapperInterface $typeMapper
     * @param FileSystemInterface $fileSystem
     * @param bool $typeHint
     * @param array $config
     * @param PhpEntityManager $entityManager
     */
    public function __construct(
        DatabaseAdapterInterface $databaseAdapter,
        PhpTypeMapperInterface $typeMapper,
        FileSystemInterface $fileSystem,
        bool $typeHint,
        array $config,
        PhpEntityManager $entityManager
    )
    {
        parent::__construct($databaseAdapter, $typeMapper, $fileSystem, $typeHint);
        $this->entityManager = $entityManager;
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function generateFactories()
    {
        $tables = $this->filterTables(
            $this->databaseAdapter->getTables(),
            $this->config['includeTables']
        );

        foreach ($tables->all() as $table) {
            $this->generateFactory($table->getName());
        }
    }

    /**
     * @param string $tableName
     * @return void
     */
    public function generateFactory(string $tableName)
    {
        $className = $this->formClassName($tableName);
        $fields = $this->databaseAdapter->getFields($tableName);
        $entityClassName = $this->entityManager->formClassName($tableName);
        $fullyQualifiedEntityClassName = $this->entityManager->getEntitiesNamespace() . '\\' . $entityClassName;

        $entityFactoryGenerator = new PhpEntityFactoryGenerator(
            new PhpEntityFactoryGeneratorConfig(
                new  PhpClassGeneratorConfig(
                    $this->config['namespace'],
                    $className,
                    new StringCollection(... [$fullyQualifiedEntityClassName]),
                    null
                ),
                $this->typeHint,
                $this->config['includeAnnotations']
            ),
            $this->formGeneratorFieldsDetails($fields),
            new PhpEntityFactoryFieldFunctionResolver(),
            $entityClassName
        );

        $entityFactoryFileAsString = $entityFactoryGenerator->generate();
        $entityFactoryFileName = $className . '.php';

        if (!$this->fileSystem->exists($this->config['outputDirectory'])) {
            $this->fileSystem->createDirectory($this->config['outputDirectory'], true);
        }

        $this->fileSystem->write(
            $this->config['outputDirectory'] . '/' . $entityFactoryFileName,
            $entityFactoryFileAsString
        );
    }

    /**
     * @param string $tableName
     * @return string
     */
    public function formClassName(string $tableName) : string
    {
        if (!isset($this->config['tableToEntityFactoryClassName'][$tableName])) {
            return snakeToPascalCase($tableName) . 'EntityFactory';
        }

        return $this->config['tableToEntityFactoryClassName'][$tableName];
    }

    /**
     * @param FieldsCollection $fields
     * @return PhpEntityFactoryFieldsCollection
     */
    private function formGeneratorFieldsDetails(FieldsCollection $fields) : PhpEntityFactoryFieldsCollection
    {
        $generatorFields = [];


        foreach ($fields->all() as $field) {
            $property = $this->entityManager->formProperty($field);
            $lengthLimit = null;
            $signed = null;

            if ($field instanceof IntegerField) {
                $signed = $field->isSigned();
                $lengthLimit = $field->getLengthInBits();
            }

            if ($field instanceof TextField
                || $field instanceof BinaryField) {
                $lengthLimit = $field->getLengthInBytes();
            }

            $generatorFields[] = new PhpEntityFactoryField(
                $field->getName(),
                $property->getName(),
                $property->getType(),
                $lengthLimit,
                $signed
            );
        }
    }
}
