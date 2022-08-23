<?php

namespace kristijorgji\UnitTests\Db\Adapters;

use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterFactory;
use kristijorgji\DbToPhp\Db\Adapters\Exceptions\InvalidDatabaseDriverException;
use kristijorgji\Tests\Helpers\TestCase;

class DatabaseAdapterFactoryTest extends TestCase
{
    /**
     * @var DatabaseAdapterFactory
     */
    private $databaseAdapterFactory;

    public function setUp(): void
    {
        $this->databaseAdapterFactory = new DatabaseAdapterFactory();
    }

    public function testGet_invalid_database_driver()
    {
        $this->expectException(InvalidDatabaseDriverException::class);
        $this->databaseAdapterFactory->get(-23, []);
    }
}
