<?php

namespace kristijorgji\DbToPhp\Managers\Php;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\Db\FieldsCollection;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpGetterGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpPropertyGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpSetterGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityGenerator;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperInterface;
use kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers;
use kristijorgji\DbToPhp\Rules\Php\PhpPropertiesCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;

class PhpEntityManager extends AbstractPhpManager
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var bool
     */
    private $typeHint;

    /**
     * @param DatabaseAdapterInterface $databaseAdapter
     * @param PhpTypeMapperInterface $typeMapper
     * @param FileSystemInterface $fileSystem
     * @param array $config
     * @param bool $typeHints
     */
    public function __construct(
        DatabaseAdapterInterface $databaseAdapter,
        PhpTypeMapperInterface $typeMapper,
        FileSystemInterface $fileSystem,
        array $config,
        bool $typeHints
    )
    {
        parent::__construct($databaseAdapter, $typeMapper, $fileSystem);
        $this->config = $config;
        $this->typeHint = $typeHints;
    }

    /**
     * @return void
     */
    public function generateEntities()
    {
        $tables = $this->filterTables(
            $this->databaseAdapter->getTables(),
            $this->config['includeTables']
        );
        foreach ($tables->all() as $table) {
            $this->generateEntity($table->getName());
        }
    }

    /**
     * @param string $tableName
     * @return void
     */
    public function generateEntity(string $tableName)
    {
        $className = $this->formClassName($tableName);
        $fields = $this->databaseAdapter->getFields($tableName);
        $properties = $this->formProperties($fields);

        $entityGeneratorConfig = new PhpEntityGeneratorConfig(
            $this->config['namespace'],
            $className,
            $this->config['includeSetters'],
            $this->config['includeGetters'],
            new PhpSetterGeneratorConfig(
                $this->config['includeAnnotations'],
                $this->typeHint,
                $this->config['fluentSetters']
            ),
            new PhpGetterGeneratorConfig(
                $this->config['includeAnnotations'],
                $this->typeHint
            ),
            new PhpPropertyGeneratorConfig(
                $this->config['includeAnnotations']
            )
        );

        $entityGenerator = new PhpEntityGenerator(
            $entityGeneratorConfig,
            $properties
        );

        $entityFileAsString = $entityGenerator->generate();
        $entityFileName = $className . '.php';

        if (!$this->fileSystem->exists($this->config['outputDirectory'])) {
            $this->fileSystem->createDirectory($this->config['outputDirectory'], true);
        }

        $this->fileSystem->write(
            $this->config['outputDirectory'] . '/' . $entityFileName,
            $entityFileAsString
        );
    }

    /**
     * @param FieldsCollection $fields
     * @return PhpPropertiesCollection
     */
    public function formProperties(FieldsCollection $fields) : PhpPropertiesCollection
    {
        $properties = [];

        foreach ($fields->all() as $field) {
            $properties[] = new PhpProperty(
                new PhpAccessModifiers($this->config['properties']['accessModifier']),
                $this->typeMapper->map($field),
                snakeToCamelCase($field->getName())
            );
        }

        return new PhpPropertiesCollection(...$properties);
    }

    /**
     * @param string $tableName
     * @return string
     */
    public function formClassName(string $tableName) : string
    {
        if (!isset($this->config['tableToEntityClassName'][$tableName])) {
            return snakeToPascalCase($tableName) . 'Entity';
        }

        return $this->config['tableToEntityClassName'][$tableName];
    }
}
