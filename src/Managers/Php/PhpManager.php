<?php

namespace kristijorgji\DbToPhp\Managers\Php;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperInterface;
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

        $this->entityFactoryManager = new PhpEntityFactoryManager(
            $this->databaseAdapter,
            $this->typeMapper,
            $this->fileSystem,
            $this->config['typeHint'],
            $this->config['factories'],
            $this->entityManager
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
