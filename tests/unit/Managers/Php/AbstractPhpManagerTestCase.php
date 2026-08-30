<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Managers\Php;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperInterface;
use kristijorgji\Tests\Helpers\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;

class AbstractPhpManagerTestCase extends TestCase
{
    protected array $config;
    protected DatabaseAdapterInterface&Stub $databaseAdapter;
    protected PhpTypeMapperInterface&Stub $typeMapper;
    protected FileSystemInterface&Stub $fileSystem;
    protected bool $typeHint;

    protected function setUp(): void
    {
        $this->config = require $this->baseTestsPath('integration/MySql/Php/config.php');

        $this->databaseAdapter = $this->createStub(DatabaseAdapterInterface::class);
        $this->typeMapper = $this->createStub(PhpTypeMapperInterface::class);
        $this->fileSystem = $this->createStub(FileSystemInterface::class);
        $this->typeHint = $this->config['typeHint'];
    }

    protected function mockDatabaseAdapter(): DatabaseAdapterInterface&MockObject
    {
        $this->databaseAdapter = $this->createMock(DatabaseAdapterInterface::class);

        return $this->databaseAdapter;
    }

    protected function mockTypeMapper(): PhpTypeMapperInterface&MockObject
    {
        $this->typeMapper = $this->createMock(PhpTypeMapperInterface::class);

        return $this->typeMapper;
    }

    protected function mockFileSystem(): FileSystemInterface&MockObject
    {
        $this->fileSystem = $this->createMock(FileSystemInterface::class);

        return $this->fileSystem;
    }
}
