<?php

namespace kristijorgji\DbToPhp\Managers;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterFactory;
use kristijorgji\DbToPhp\FileSystem\FileSystem;
use kristijorgji\DbToPhp\Managers\Exceptions\InvalidProgrammingLanguageException;
use kristijorgji\DbToPhp\Managers\Php\PhpManager;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperFactory;

class ManagerFactory
{
    /**
     * @var DatabaseAdapterFactory
     */
    private $databaseAdapterFactory;

    /**
     * @param DatabaseAdapterFactory $databaseAdapterFactory
     */
    public function __construct(
        DatabaseAdapterFactory $databaseAdapterFactory
    )
    {
        $this->databaseAdapterFactory = $databaseAdapterFactory;
    }

    /**
     * @param array $config
     * @return ManagerContract
     * @throws InvalidProgrammingLanguageException
     */
    public function get(array $config) : ManagerContract
    {
        $fileSystem = new FileSystem();

        $databaseAdapter = $this->databaseAdapterFactory->get(
            $config['databaseDriver'],
            $config['connection']
        );

        $typeMapper = (new PhpTypeMapperFactory())->get($config['databaseDriver']);
        return new PhpManager($config, $databaseAdapter, $typeMapper, $fileSystem);
    }
}
