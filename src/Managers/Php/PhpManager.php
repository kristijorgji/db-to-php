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

class PhpManager extends AbstractPhpManager implements ManagerContract
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var PhpEntityManager
     */
    private $entityManager;

    /**
     * @var PhpEntityFactoryManager
     */
    private $entityFactoryManager;

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
        parent::__construct($databaseAdapter, $typeMapper, $fileSystem, $config['typeHint']);
        $this->config = $config;
        $this->databaseAdapter = $databaseAdapter;
        $this->typeMapper = $typeMapper;
        $this->fileSystem = $fileSystem;

        $this->entityManager = new PhpEntityManager(
            $this->databaseAdapter,
            $this->typeMapper,
            $this->fileSystem,
            $this->config['typeHint'],
            $this->config['entities']
        );

        $entityFactoryManagerConfig = $this->config['factories'];
        $entityFactoryManagerConfig['tableToEntityClassName'] = [];

        foreach ($this->databaseAdapter->getTables()->all() as $table) {
            $entityFactoryManagerConfig['tableToEntityClassName'][]
                = $this->entityManager->formClassName($table->getName());
        }

        $entityFactoryManagerConfig['entitiesNamespace'] = $this->config['entities']['namespace'];

        $this->entityFactoryManager = new PhpEntityFactoryManager(
            $this->databaseAdapter,
            $this->typeMapper,
            $this->fileSystem,
            $this->config['typeHint'],
            $entityFactoryManagerConfig
        );
    }

    /**
     * @return void
     */
    public function generateEntities()
    {
        $this->entityManager->generateEntities();
    }

    /**
     * @param string $tableName
     * @return void
     */
    public function generateEntity(string $tableName)
    {
        $this->entityManager->generateEntity($tableName);
    }

    /**
     * @return void
     */
    public function generateFactories()
    {
        $this->entityFactoryManager->generateFactories();
    }

    /**
     * @param string $tableName
     * @return void
     */
    public function generateFactory(string $tableName)
    {
        $this->entityFactoryManager->generateFactory($tableName);
    }
}
