<?php

namespace kristijorgji\DbToPhp\Managers\Php;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpClassGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityFactoryGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryGenerator;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperInterface;
use kristijorgji\DbToPhp\Support\StringCollection;

class PhpEntityFactoryManager extends AbstractPhpManager
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param DatabaseAdapterInterface $databaseAdapter
     * @param PhpTypeMapperInterface $typeMapper
     * @param FileSystemInterface $fileSystem
     * @param array $config
     * @param bool $typeHint
     */
    public function __construct(
        DatabaseAdapterInterface $databaseAdapter,
        PhpTypeMapperInterface $typeMapper,
        FileSystemInterface $fileSystem,
        bool $typeHint,
        array $config
    )
    {
        parent::__construct($databaseAdapter, $typeMapper, $fileSystem, $typeHint);
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
        $entityClassName = $this->resolveReturnedEntity($tableName);
        $fullyQualifiedEntityClassName = $this->config['entitiesNamespace'] . '\\' . $entityClassName;

        $entityFactoryGenerator = new PhpEntityFactoryGenerator(
            new PhpEntityFactoryGeneratorConfig(
                new  PhpClassGeneratorConfig(
                    $this->config['namespace'],
                    $className,
                    new StringCollection(... [$fullyQualifiedEntityClassName]),
                    null
                ),
                $this->typeHint
            ),
            $fields,
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

    public function resolveReturnedEntity(string $tableName) : string
    {
        if (!isset($this->config['tableToEntityClassName'][$tableName])) {
            return snakeToPascalCase($tableName) . 'EntityFactory';
        }

        return $this->config['tableToEntityClassName'][$tableName];
    }
}
