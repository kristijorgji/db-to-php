<?php

namespace kristijorgji\UnitTests\Mappers\Types\Php;

use kristijorgji\DbToPhp\Db\Fields\BinaryField;
use kristijorgji\DbToPhp\Db\Fields\BoolField;
use kristijorgji\DbToPhp\Db\Fields\DateField;
use kristijorgji\DbToPhp\Db\Fields\DecimalField;
use kristijorgji\DbToPhp\Db\Fields\DoubleField;
use kristijorgji\DbToPhp\Db\Fields\EnumField;
use kristijorgji\DbToPhp\Db\Fields\Field;
use kristijorgji\DbToPhp\Db\Fields\FloatField;
use kristijorgji\DbToPhp\Db\Fields\IntegerField;
use kristijorgji\DbToPhp\Db\Fields\TextField;
use kristijorgji\DbToPhp\Db\Fields\YearField;
use kristijorgji\DbToPhp\Mappers\Types\Exceptions\UnknownDatabaseFieldTypeException;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapper;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;
use kristijorgji\DbToPhp\Support\StringCollection;
use kristijorgji\Tests\Helpers\TestCase;

class PhpTypeMapperTest extends TestCase
{
    /**
     * @var PhpTypeMapper
     */
    private $mapper;

    public function setUp()
    {
        $this->mapper = new PhpTypeMapper();
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

    public function testMap_unknown_type()
    {
        $field = new TestDbField('test', false);
        $this->expectException(UnknownDatabaseFieldTypeException::class);
        $this->mapper->map($field);
    }

    public function mapProvider()
    {
        $name = self::randomString(2);

        return [
            [new BinaryField($name, false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new BoolField($name, false), new PhpType(new PhpTypes(PhpTypes::BOOL), false)],
            [new DateField($name, false, DateField::MYSQL_TIMESTAMP), new PhpType(new PhpTypes(PhpTypes::STRING), false)],

            [new DecimalField($name, false, 4), new PhpType(new PhpTypes(PhpTypes::INTEGER), false)],
            'decimal_with_fractional' => [new DecimalField($name, false, 16, 4), new PhpType(new PhpTypes(PhpTypes::FLOAT), false)],
            'decimal_without_fractional' => [new DecimalField($name, false, 20, 0), new PhpType(new PhpTypes(PhpTypes::INTEGER), false)],

            [new DoubleField($name, false), new PhpType(new PhpTypes(PhpTypes::FLOAT), false)],
            [new EnumField($name, false, new StringCollection('d')), new PhpType(new PhpTypes(PhpTypes::STRING), false)],
            [new FloatField($name, false), new PhpType(new PhpTypes(PhpTypes::FLOAT), false)],
            [new IntegerField($name, false), new PhpType(new PhpTypes(PhpTypes::INTEGER), false)],
            [new IntegerField($name, true), new PhpType(new PhpTypes(PhpTypes::INTEGER), true)],
            [new TextField($name, false), new PhpType(new PhpTypes(PhpTypes::STRING), false)],

            'year' => [new YearField($name, true), new PhpType(new PhpTypes(PhpTypes::INTEGER), true)],
        ];
    }
}
