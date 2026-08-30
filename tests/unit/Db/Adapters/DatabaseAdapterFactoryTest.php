<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Db\Adapters;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterFactory;
use kristijorgji\DbToPhp\Db\Adapters\Exceptions\InvalidDatabaseDriverException;
use kristijorgji\Tests\Helpers\TestCase;

final class DatabaseAdapterFactoryTest extends TestCase
{
    private DatabaseAdapterFactory $databaseAdapterFactory;

    protected function setUp(): void
    {
        $this->databaseAdapterFactory = new DatabaseAdapterFactory;
    }

    public function testGet_invalid_database_driver(): void
    {
        $this->expectException(InvalidDatabaseDriverException::class);
        $this->databaseAdapterFactory->get('invalid-driver', []);
    }
}
