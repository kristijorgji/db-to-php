<?php

namespace kristijorgji\UnitTests\Mappers\Types\Php;

use kristijorgji\DbToPhp\DatabaseDrivers;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapper;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperFactory;

class PhpTypeMapperFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PhpTypeMapperFactory
     */
    protected $typeMapperFactory;

    public function setUp()
    {
        $this->typeMapperFactory = new PhpTypeMapperFactory();
    }

    /**
     * @dataProvider getProvider
     * @param string $databaseDriver
     * @param string $expectedMapperClass
     */
    public function testGet(string $databaseDriver, string $expectedMapperClass)
    {
        $actualMapper = $this->typeMapperFactory->get($databaseDriver);
        $this->assertInstanceOf($expectedMapperClass, $actualMapper);
    }

    public function testGet_invalid_database_driver()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->typeMapperFactory->get(-2);
    }

    public function getProvider()
    {
        return [
            [DatabaseDrivers::MYSQL, PhpTypeMapper::class]
        ];
    }
}
