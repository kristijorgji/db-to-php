<?php

namespace kristijorgji\UnitTests\Db;

use kristijorgji\DbToPhp\Db\Adapters\MySqlAdapter;
use kristijorgji\DbToPhp\Db\Field;
use kristijorgji\DbToPhp\Db\FieldsCollection;
use kristijorgji\Tests\Helpers\MySqlTestCase;

class MySqlAdapterTest extends MySqlTestCase
{
    /**
     * @var MySqlAdapter
     */
    private $databaseAdapter;

    public function setUp()
    {
        $this->databaseAdapter = new MySqlAdapter(
            self::$mysqlConnection['host'],
            self::$mysqlConnection['port'],
            self::$mysqlConnection['database'],
            self::$mysqlConnection['username'],
            self::$mysqlConnection['password']
        );
    }

    public function tearDown()
    {
        $this->databaseAdapter = null;
    }

    public function testGetTables()
    {
        $expectedTableNames = [
            'binarius',
            'test',
            'test_2'
        ];

        $tables = $this->databaseAdapter->getTables();
        foreach ($tables as $table) {
            $this->assertTrue(in_array($table->getName(), $expectedTableNames));
        }
    }

    public function testGetFields()
    {
        $fields = $this->databaseAdapter->getFields('test');
        $expectedFields = new FieldsCollection(... [
            new Field('id', 'bigint(20) unsigned', false),
            new Field('event', 'varchar(50)', false),
            new Field('payload', 'longtext', false),
            new Field('status', 'enum(\'jaru\',\'naru\',\'daru\')', false),
            new Field('super_status', 'enum(\'1\',\'4\',\'111\')', false),
            new Field('active', 'bit(4)', false),
            new Field('file', 'blob', false),
            new Field('time', 'time', false),
            new Field('can_be_nulled', 'int(11)', true),
            new Field('created_at', 'timestamp', false),
            new Field('updated_at', 'timestamp', false),
            new Field('new_column', 'bit(1)', true),
        ]);

        $this->assertEquals($expectedFields, $fields);
    }

    /**
     * @return string
     */
    public static function getDumpPath() : string
    {
        return __DIR__ . '/test-mysql-db.sql';
    }
}
