<?php

namespace kristijorgji\UnitTests\Db\Adapters\MySql;

use kristijorgji\DbToPhp\Db\Adapters\MySql\MySqlAdapter;
use kristijorgji\DbToPhp\Db\Fields\BinaryField;
use kristijorgji\DbToPhp\Db\Fields\BoolField;
use kristijorgji\DbToPhp\Db\Fields\DoubleField;
use kristijorgji\DbToPhp\Db\Fields\EnumField;
use kristijorgji\DbToPhp\Db\Fields\Field;
use kristijorgji\DbToPhp\Db\Fields\FieldsCollection;
use kristijorgji\DbToPhp\Db\Fields\FloatField;
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
            new IntegerField('id', 'bigint(20) unsigned', false, 64, false),
            new TextField('event', 'varchar(50)', false, 50),
            new TextField('payload', 'longtext', false),
            new EnumField(
                'status',
                'enum(\'jaru\',\'naru\',\'daru\')',
                false,
                new StringCollection(... ['jaru', 'naru', 'daru'])
            ),
            new EnumField(
                'super_status',
                'enum(\'1\',\'4\',\'111\')',
                false,
                new StringCollection(... ['1', '4', '111'])
            ),
            new BoolField('active', 'bit(4)', false),
            new TextField('file', 'blob', false),
            new TextField('time', 'time', false),
            new IntegerField('can_be_nulled', 'int(11)', true, 32, true),
            new TextField('created_at', 'timestamp', false),
            new TextField('updated_at', 'timestamp', false),
            new BoolField('new_column', 'bit(1)', true),
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
