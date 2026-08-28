<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Managers\Php;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\DbToPhp\Managers\Exceptions\GenerateException;
use kristijorgji\DbToPhp\Managers\GenerateResponse;
use kristijorgji\DbToPhp\Managers\ManagerContract;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperInterface;

class PhpManager extends AbstractPhpManager implements ManagerContract
{
    private PhpEntityManager $entityManager;
    private PhpEntityFactoryManager $entityFactoryManager;

    public function __construct(
        protected array $config,
        DatabaseAdapterInterface $databaseAdapter,
        PhpTypeMapperInterface $typeMapper,
        FileSystemInterface $fileSystem,
    ) {
        parent::__construct($databaseAdapter, $typeMapper, $fileSystem, $config['typeHint']);
        $this->databaseAdapter = $databaseAdapter;
        $this->typeMapper = $typeMapper;
        $this->fileSystem = $fileSystem;

        $this->entityManager = new PhpEntityManager(
            $this->databaseAdapter,
            $this->typeMapper,
            $this->fileSystem,
            $this->config['typeHint'],
            $this->config['entities'],
        );

        $this->entityFactoryManager = new PhpEntityFactoryManager(
            $this->databaseAdapter,
            $this->typeMapper,
            $this->fileSystem,
            $this->config['typeHint'],
            $this->config['factories'],
            $this->entityManager,
        );
    }

    /**
     * @throws GenerateException
     */
    public function generateEntities() : GenerateResponse
    {
        return $this->entityManager->generateEntities();
    }

    /**
     * @throws GenerateException
     */
    public function generateFactories() : GenerateResponse
    {
        return $this->entityFactoryManager->generateFactories();
    }
}
