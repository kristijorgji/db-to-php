<?php

namespace kristijorgji\DbToPhp\Managers\Php;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\Db\FieldsCollection;
use kristijorgji\DbToPhp\Db\TablesCollection;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpGetterGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpPropertyGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpSetterGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityGenerator;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityGeneratorConfig;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperInterface;
use kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers;
use kristijorgji\DbToPhp\Rules\Php\PhpPropertiesCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\DbToPhp\Managers\ManagerContract;

class PhpManager extends ManagerContract
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var DatabaseAdapterInterface
     */
    protected $databaseAdapter;

    /**
     * @var PhpTypeMapperInterface
     */
    protected $typeMapper;

    /**
     * @var FileSystemInterface
     */
    private $fileSystem;

    /**
     * @param array $config
     * @param DatabaseAdapterInterface $databaseAdapter
     * @param PhpTypeMapperInterface $typeMapper
     * @param FileSystemInterface $fileSystem
     */
    public function __construct(
        array $config,
        DatabaseAdapterInterface $databaseAdapter,
        PhpTypeMapperInterface $typeMapper,
        FileSystemInterface $fileSystem
    )
    {
        $this->config = $config;
        $this->databaseAdapter = $databaseAdapter;
        $this->typeMapper = $typeMapper;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @return void
     */
    public function generateEntities()
    {
        $tables = $this->filterEntityTables($this->databaseAdapter->getTables());
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
            $this->config['entities']['namespace'],
            $className,
            $this->config['entities']['includeSetters'],
            $this->config['entities']['includeGetters'],
            new PhpSetterGeneratorConfig(
                $this->config['entities']['includeAnnotations'],
                $this->config['typeHint'],
                $this->config['entities']['fluentSetters']
            ),
            new PhpGetterGeneratorConfig(
                $this->config['entities']['includeAnnotations'],
                $this->config['typeHint']
            ),
            new PhpPropertyGeneratorConfig(
                $this->config['entities']['includeAnnotations']
            )
        );

        $entityGenerator = new PhpEntityGenerator(
            $entityGeneratorConfig,
            $properties
        );

        $entityFileAsString = $entityGenerator->generate();
        $entityFileName = $className . '.php';

        if (!$this->fileSystem->exists($this->config['entities']['outputDirectory'])) {
            $this->fileSystem->createDirectory($this->config['entities']['outputDirectory'], true);
        }

        $this->fileSystem->write(
            $this->config['entities']['outputDirectory'] . '/' . $entityFileName,
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
                new PhpAccessModifiers($this->config['entities']['properties']['accessModifier']),
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
        if (!isset($this->config['entities']['tableToEntityClassName'][$tableName])) {
            return snakeToPascalCase($tableName) . 'Entity';
        }

        return $this->config['entities']['tableToEntityClassName'][$tableName];
    }

    /**
     * @param TablesCollection $tables
     * @return TablesCollection
     */
    protected function filterEntityTables(TablesCollection $tables) : TablesCollection
    {
        if ($this->config['entities']['includeTables'][0] === '*') {
            return $tables;
        }

        $selectedTables = [];

        foreach ($tables->all() as $table) {
            if (in_array($table->getName(), $this->config['entities']['includeTables'])) {
                $selectedTables[] = $table;
            }
        }

        return new TablesCollection(...$selectedTables);
    }
}
