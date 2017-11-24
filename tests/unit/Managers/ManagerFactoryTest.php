<?php

namespace kristijorgji\UnitTests\Managers;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterFactory;
use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\Languages;
use kristijorgji\DbToPhp\Managers\Exceptions\InvalidProgrammingLanguageException;
use kristijorgji\DbToPhp\Managers\ManagerFactory;
use kristijorgji\DbToPhp\Managers\Php\PhpManager;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperFactory;
use kristijorgji\Tests\Helpers\TestCase;

class ManagerFactoryTest extends TestCase
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $databaseAdapterFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $typeMapperFactory;

    /**
     * @var ManagerFactory
     */
    private $managerFactory;

    public function setUp()
    {
        $this->config = [
            'databaseDriver' => \kristijorgji\DbToPhp\DatabaseDrivers::MYSQL,
            'connection' => [
                'host' => '127.0.0.1',
                'port' => 3306,
                'database' => 'db_to_php',
                'username' => 'root',
                'password' => 'Test123@',
            ]
        ];

        $this->databaseAdapterFactory = $this->getMockBuilder(DatabaseAdapterFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->typeMapperFactory = $this->getMockBuilder(PhpTypeMapperFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->managerFactory = new ManagerFactory(
            $this->databaseAdapterFactory,
            $this->typeMapperFactory
        );
    }

    /**
     * @dataProvider getProvider
     * @param array $config
     * @param string $expectedManagerClass
     */
    public function testGet(array $config, string $expectedManagerClass)
    {
        $this->databaseAdapterFactory->expects($this->once())
            ->method('get')
            ->with($config['databaseDriver'], $config['connection'])
            ->willReturn($this->getMockBuilder(DatabaseAdapterInterface::class)->getMock());

        $actualManager = $this->managerFactory->get($config);

        $this->assertEquals($expectedManagerClass, get_class($actualManager));
    }

    public function getProvider()
    {
        $config = [
            'typeHint' => true,
            'databaseDriver' => \kristijorgji\DbToPhp\DatabaseDrivers::MYSQL,
            'connection' => [],
            'entities' => [],
            'factories' => []
        ];

        return [
            array_values(array_map(function() use ($config) {
                return [$config, PhpManager::class];
            }, range(0, 0)))[0]
        ];
    }
}
