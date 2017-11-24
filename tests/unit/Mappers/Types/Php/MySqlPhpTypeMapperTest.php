<?php

namespace kristijorgji\UnitTests\Mappers\Types\Php;

use kristijorgji\DbToPhp\Db\Field;
use kristijorgji\DbToPhp\Mappers\Types\Exceptions\UnknownDatabaseFieldTypeException;
use kristijorgji\DbToPhp\Mappers\Types\Php\MySqlPhpTypeMapper;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;
use kristijorgji\Tests\Helpers\TestCase;

class MySqlPhpTypeMapperTest extends TestCase
{
    /**
     * @var MySqlPhpTypeMapper
     */
    private $mapper;

    public function setUp()
    {
        $this->mapper = new MySqlPhpTypeMapper();
    }

    /**
     * @dataProvider mapProvider
     * @param Field $field
     * @param $expectedType
     */
    public function testMap(Field $field, $expectedType)
    {
        $actualType = $this->mapper->map($field);
        $this->assertEquals($expectedType, $actualType);
    }

    public function testMap_unkown_type()
    {
        $field = new Field('test', 'dlongtext', false);
        $this->expectException(UnknownDatabaseFieldTypeException::class);
        $this->mapper->map($field);
    }

    public function mapProvider()
    {
        return [
            [new Field('test', 'bigint(20) unsigned', false), new PhpType(new PhpTypes(PhpTypes::INTEGER), false)],
            [new Field('test', 'bigint(20) unsigned', true), new PhpType(new PhpTypes(PhpTypes::INTEGER), true)],
            [new Field('test', 'bigint(20)', false), new PhpType(new PhpTypes(PhpTypes::INTEGER), false)],
            [new Field('test', 'int(11)', false), new PhpType(new PhpTypes(PhpTypes::INTEGER), false)],
            [new Field('test', 'int(11) unsigned', false), new PhpType(new PhpTypes(PhpTypes::INTEGER), false)],

            [new Field('test', 'float', false), new PhpType(new PhpTypes(PhpTypes::FLOAT), false)],
            [new Field('test', 'double', false), new PhpType(new PhpTypes(PhpTypes::FLOAT), false)],

            [new Field('test', 'decimal(20)', false), new PhpType(new PhpTypes(PhpTypes::FLOAT), false)],
            [new Field('test', 'decimal(18,4)', false), new PhpType(new PhpTypes(PhpTypes::FLOAT), false)],
            [new Field('test', 'dec(18,4)', false), new PhpType(new PhpTypes(PhpTypes::FLOAT), false)],
            [new Field('test', 'real(10)', false), new PhpType(new PhpTypes(PhpTypes::FLOAT), false)],

            [new Field('test', 'tinyint(1)', false), new PhpType(new PhpTypes(PhpTypes::BOOL), false)],
            [new Field('test', 'bit', false), new PhpType(new PhpTypes(PhpTypes::BOOL), false)],

            [new Field('test', 'binary(1)', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new Field('test', 'varbinary(4)', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],

            [new Field('test', 'blob', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new Field('test', 'tinyblob', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new Field('test', 'smallblob', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new Field('test', 'mediumblob', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new Field('test', 'longblob', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],

            [new Field('test', 'enum(\'1\',\'4\',\'111\')', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],

            [new Field('test', 'varchar(50)', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new Field('test', 'char(2)', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],

            [new Field('test', 'text', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new Field('test', 'tinytext', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new Field('test', 'smalltext', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new Field('test', 'mediumtext', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new Field('test', 'longtext', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],

            [new Field('test', 'time', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new Field('test', 'timestamp', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
        ];
    }
}
