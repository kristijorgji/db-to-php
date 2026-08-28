<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Mappers\Types\Php;

use InvalidArgumentException;
use kristijorgji\DbToPhp\DatabaseDrivers;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapper;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperFactory;
use kristijorgji\Tests\Helpers\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class PhpTypeMapperFactoryTest extends TestCase
{
    protected PhpTypeMapperFactory $typeMapperFactory;

    protected function setUp(): void
    {
        $this->typeMapperFactory = new PhpTypeMapperFactory;
    }

    #[DataProvider('getProvider')]
    public function testGet(string $databaseDriver, string $expectedMapperClass): void
    {
        $actualMapper = $this->typeMapperFactory->get($databaseDriver);
        $this->assertInstanceOf($expectedMapperClass, $actualMapper);
    }

    public function testGet_invalid_database_driver(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->typeMapperFactory->get('invalid-driver');
    }

    public static function getProvider(): array
    {
        return [
            [DatabaseDrivers::MYSQL, PhpTypeMapper::class],
        ];
    }
}
