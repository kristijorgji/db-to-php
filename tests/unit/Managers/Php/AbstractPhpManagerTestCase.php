<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Managers\Php;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperInterface;
use kristijorgji\Tests\Helpers\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class AbstractPhpManagerTestCase extends TestCase
{
    protected array $config;
    protected DatabaseAdapterInterface&MockObject $databaseAdapter;
    protected PhpTypeMapperInterface&MockObject $typeMapper;
    protected FileSystemInterface&MockObject $fileSystem;
    protected bool $typeHint;

    protected function setUp(): void
    {
        $this->config = require $this->baseTestsPath('integration/MySql/Php/config.php');

        $this->databaseAdapter = $this->getMockBuilder(DatabaseAdapterInterface::class)->getMock();
        $this->typeMapper = $this->getMockBuilder(PhpTypeMapperInterface::class)->getMock();
        $this->fileSystem = $this->getMockBuilder(FileSystemInterface::class)->getMock();
        $this->typeHint = $this->config['typeHint'];
    }
}
