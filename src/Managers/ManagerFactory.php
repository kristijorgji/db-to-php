<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Managers;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterFactory;
use kristijorgji\DbToPhp\FileSystem\FileSystem;
use kristijorgji\DbToPhp\Managers\Exceptions\InvalidProgrammingLanguageException;
use kristijorgji\DbToPhp\Managers\Php\PhpManager;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperFactory;

class ManagerFactory
{
    public function __construct(
        private readonly DatabaseAdapterFactory $databaseAdapterFactory,
    ) {
    }

    /**
     * @throws InvalidProgrammingLanguageException
     */
    public function get(array $config) : ManagerContract
    {
        $fileSystem = new FileSystem;

        $databaseAdapter = $this->databaseAdapterFactory->get(
            $config['databaseDriver'],
            $config['connection'],
        );

        $typeMapper = (new PhpTypeMapperFactory)->get($config['databaseDriver']);
        return new PhpManager($config, $databaseAdapter, $typeMapper, $fileSystem);
    }
}
