<?php

namespace kristijorgji\UnitTests\Managers\Php;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperInterface;
use kristijorgji\Tests\Helpers\TestCase;

class AbstractPhpManagerTestCase extends TestCase
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $databaseAdapter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $typeMapper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $fileSystem;

    /**
     * @var bool
     */
    protected $typeHint;

    public function setUp()
    {
        $this->config = require $this->baseTestsPath('integration/MySql/Php/config.php');

        $this->databaseAdapter = $this->getMockBuilder(DatabaseAdapterInterface::class)->getMock();
        $this->typeMapper = $this->getMockBuilder(PhpTypeMapperInterface::class)->getMock();
        $this->fileSystem = $this->getMockBuilder(FileSystemInterface::class)->getMock();
        $this->typeHint = $this->config['typeHint'];
    }
}
