<?php

namespace kristijorgji\UnitTests\Mappers\Types\Php;

use kristijorgji\DbToPhp\Db\Fields\BinaryField;
use kristijorgji\DbToPhp\Db\Fields\BoolField;
use kristijorgji\DbToPhp\Db\Fields\DecimalField;
use kristijorgji\DbToPhp\Db\Fields\DoubleField;
use kristijorgji\DbToPhp\Db\Fields\Field;
use kristijorgji\DbToPhp\Db\Fields\FloatField;
use kristijorgji\DbToPhp\Db\Fields\IntegerField;
use kristijorgji\DbToPhp\Db\Fields\TextField;
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
        $field = new TestDbField('test', 'dlongtext', false);
        $this->expectException(UnknownDatabaseFieldTypeException::class);
        $this->mapper->map($field);
    }

    public function mapProvider()
    {
        return [
            [new IntegerField('test', 'bigint(20) unsigned', false), new PhpType(new PhpTypes(PhpTypes::INTEGER), false)],
            [new IntegerField('test', 'bigint(20) unsigned', true), new PhpType(new PhpTypes(PhpTypes::INTEGER), true)],
            [new IntegerField('test', 'bigint(20)', false), new PhpType(new PhpTypes(PhpTypes::INTEGER), false)],
            [new IntegerField('test', 'int(11)', false), new PhpType(new PhpTypes(PhpTypes::INTEGER), false)],
            [new IntegerField('test', 'int(11) unsigned', false), new PhpType(new PhpTypes(PhpTypes::INTEGER), false)],

            [new FloatField('test', 'float', false), new PhpType(new PhpTypes(PhpTypes::FLOAT), false)],
            [new DoubleField('test', 'double', false), new PhpType(new PhpTypes(PhpTypes::FLOAT), false)],

            [new FloatField('test', 'decimal(20)', false), new PhpType(new PhpTypes(PhpTypes::FLOAT), false)],
            [new FloatField('test', 'decimal(18,4)', false), new PhpType(new PhpTypes(PhpTypes::FLOAT), false)],
            [new FloatField('test', 'dec(18,4)', false), new PhpType(new PhpTypes(PhpTypes::FLOAT), false)],
            [new FloatField('test', 'real(10)', false), new PhpType(new PhpTypes(PhpTypes::FLOAT), false)],

            [new BoolField('test', 'tinyint(1)', false), new PhpType(new PhpTypes(PhpTypes::BOOL), false)],
            [new BoolField('test', 'bit', false), new PhpType(new PhpTypes(PhpTypes::BOOL), false)],

            [new BinaryField('test', 'binary(1)', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new BinaryField('test', 'varbinary(4)', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],

            [new TextField('test', 'blob', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new TextField('test', 'tinyblob', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new TextField('test', 'smallblob', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new TextField('test', 'mediumblob', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new TextField('test', 'longblob', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],

            [new TextField('test', 'enum(\'1\',\'4\',\'111\')', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],

            [new TextField('test', 'varchar(50)', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new TextField('test', 'char(2)', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],

            [new TextField('test', 'text', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new TextField('test', 'tinytext', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new TextField('test', 'smalltext', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new TextField('test', 'mediumtext', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new TextField('test', 'longtext', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],

            [new DecimalField('test', 'year(4)', false, 4), new PhpType(new PhpTypes(PhpTypes::INTEGER), false)],
            [new DecimalField('test', 'decimal(20, 4)', false, 16, 4), new PhpType(new PhpTypes(PhpTypes::FLOAT), false)],

            [new TextField('test', 'time', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new TextField('test', 'datetime', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new TextField('test', 'timestamp', false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
        ];
    }
}
