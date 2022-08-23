<?php

namespace kristijorgji\UnitTests\Db\Adapters\MySql;

use kristijorgji\DbToPhp\Db\Adapters\MySql\MySqlAdapter;
use kristijorgji\DbToPhp\Db\Fields\BoolField;
use kristijorgji\DbToPhp\Db\Fields\DateField;
use kristijorgji\DbToPhp\Db\Fields\EnumField;
use kristijorgji\DbToPhp\Db\Fields\FieldsCollection;
use kristijorgji\DbToPhp\Db\Fields\IntegerField;
use kristijorgji\DbToPhp\Db\Fields\TextField;
use kristijorgji\DbToPhp\Support\StringCollection;
use kristijorgji\Tests\Helpers\MySqlTestCase;

class MySqlAdapterTest extends MySqlTestCase
{
    /**
     * @var MySqlAdapter
     */
    private $databaseAdapter;

    public function setUp(): void
    {
        $this->databaseAdapter = new MySqlAdapter(
            self::$mysqlConnection['host'],
            self::$mysqlConnection['port'],
            self::$mysqlConnection['database'],
            self::$mysqlConnection['username'],
            self::$mysqlConnection['password']
        );
    }

    public function tearDown(): void
    {
        $this->databaseAdapter = null;
    }

    public function testGetTables()
    {
        $expectedTableNames = [
            'binarius',
            'special',
            'test',
            'test_2',
            'times'
        ];

        $tables = $this->databaseAdapter->getTables();
        print_r($tables);
        foreach ($tables as $table) {
            $this->assertTrue(in_array($table->getName(), $expectedTableNames));
        }
    }

    public function testGetFields()
    {
        $fields = $this->databaseAdapter->getFields('test');
        $expectedFields = new FieldsCollection(... [
            new IntegerField('id',  false, 64, false),
            new TextField('event',  false, 50),
            new TextField('payload',  false),
            new EnumField(
                'status',
                false,
                new StringCollection(... ['jaru', 'naru', 'daru'])
            ),
            new EnumField(
                'super_status',
                false,
                new StringCollection(... ['1', '4', '111'])
            ),
            new BoolField('active',  false),
            new TextField('file',  false),
            new TextField('time',  false),
            new IntegerField('can_be_nulled',  true, 32, true),
            new DateField('created_at',  false, DateField::MYSQL_TIMESTAMP),
            new DateField('updated_at',  false, DateField::MYSQL_TIMESTAMP),
            new BoolField('new_column',  true),
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
