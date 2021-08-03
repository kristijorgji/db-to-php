<?php

namespace kristijorgji\UnitTests\Db\Adapters\MySql;

use kristijorgji\DbToPhp\Db\Adapters\MySql\Exceptions\UnknownMySqlTypeException;
use kristijorgji\DbToPhp\Db\Adapters\MySql\MySqlFieldResolver;
use kristijorgji\DbToPhp\Db\Fields\BinaryField;
use kristijorgji\DbToPhp\Db\Fields\BoolField;
use kristijorgji\DbToPhp\Db\Fields\DateField;
use kristijorgji\DbToPhp\Db\Fields\DecimalField;
use kristijorgji\DbToPhp\Db\Fields\DoubleField;
use kristijorgji\DbToPhp\Db\Fields\EnumField;
use kristijorgji\DbToPhp\Db\Fields\Field;
use kristijorgji\DbToPhp\Db\Fields\FloatField;
use kristijorgji\DbToPhp\Db\Fields\IntegerField;
use kristijorgji\DbToPhp\Db\Fields\JsonField;
use kristijorgji\DbToPhp\Db\Fields\TextField;
use kristijorgji\DbToPhp\Db\Fields\YearField;
use kristijorgji\DbToPhp\Support\StringCollection;
use kristijorgji\Tests\Helpers\TestCase;

class MySqlFieldResolverTest extends TestCase
{
    /**
     * @var MySqlFieldResolver
     */
    private $fieldResolver;

    public function setUp()
    {
        $this->fieldResolver = new MySqlFieldResolver();
    }

    public function testResolveField_unknown_field()
    {
        $actual = $this->fieldResolver->resolveField(
            self::randomString(),
            self::randomString(),
            self::randomString()
        );

        $this->assertInstanceOf(TextField::class, $actual);
    }

    /**
     * @dataProvider resolveFieldProvider
     * @param string $name
     * @param string $type
     * @param string $null
     * @param Field $expectedField
     */
    public function testResolveField(string $name, string $type, string $null, Field $expectedField)
    {
        $actualField = $this->fieldResolver->resolveField(
            $name,
            $type,
            $null
        );

        $this->assertEquals($expectedField, $actualField);
    }

    public function resolveFieldProvider()
    {
        $name = self::randomString(4);

        $h = function (Field $field, string $mysqlType) use ($name) {
            return [
                $name, $mysqlType, ($field->isNullable() ? 'YES' : 'NO'), $field
            ];
        };

        return [
            'nullable' => $h(new TextField($name, true, 1), 'char(1)'),
            'not_nullable' => $h(new TextField($name, false, 1), 'char(1)'),
            'enum' => $h(
                new EnumField($name, false, new StringCollection(
                    ... ['j,aru', 'naru', 'daru'])),
                'enum(\'j,aru\',\'naru\',\'daru\')'
            ),
            'enum_1' => $h(new EnumField($name, false, new StringCollection(
                ... ['1', '4', '111'])),
                'enum(\'1\',\'4\',\'111\')'
            ),

            'unsigned_int8_no_length' => $h(new IntegerField($name, false, 8, false), 'tinyint unsigned'),
            'unsigned_int8' => $h(new IntegerField($name, false, 8, false), 'tinyint(3) unsigned'),
            'signed_int8' => $h(new IntegerField($name, false, 8, true), 'tinyint(3)'),
            'signed_int8_no_length' => $h(new IntegerField($name, false, 8, true), 'tinyint'),

            'unsigned_int16_no_length' => $h(new IntegerField($name, false, 16, false), 'smallint unsigned'),
            'unsigned_int16' => $h(new IntegerField($name, false, 16, false), 'smallint(5) unsigned'),
            'signed_int16' => $h(new IntegerField($name, false, 16, true), 'smallint(5)'),
            'signed_int16_no_length' => $h(new IntegerField($name, false, 16, true), 'smallint'),

            'unsigned_int24_no_length' => $h(new IntegerField($name, false, 24, false), 'mediumint unsigned'),
            'unsigned_int24' => $h(new IntegerField($name, false, 24, false), 'mediumint(9) unsigned'),
            'signed_int24' => $h(new IntegerField($name, false, 24, true), 'mediumint(9)'),
            'signed_int24_no_length' => $h(new IntegerField($name, false, 24, true), 'mediumint'),

            'unsigned_int32_no_length' => $h(new IntegerField($name, false, 32, false), 'int unsigned'),
            'unsigned_int32' => $h(new IntegerField($name, false, 32, false), 'int(11) unsigned'),
            'signed_int32' => $h(new IntegerField($name, false, 32, true), 'int(11)'),
            'signed_int32_no_length' => $h(new IntegerField($name, false, 32, true), 'int'),

            'unsigned_int64_no_length' => $h(new IntegerField($name, false, 64, false), 'bigint unsigned'),
            'unsigned_int64' => $h(new IntegerField($name, false, 64, false), 'bigint(20) unsigned'),
            'signed_int64' => $h(new IntegerField($name, false, 64, true), 'bigint(20)'),
            'signed_int64_no_length' => $h(new IntegerField($name, false, 64, true), 'bigint'),

            $h(new FloatField($name, false), 'float'),

            'double' => $h(new DoubleField($name, false), 'double'),
            'real' => $h(new DoubleField($name, false), 'real(10)'),

            'signed_decimal_no_fractionals' => $h(new DecimalField($name, false, 20, 0, true), 'decimal(20,0)'),
            'unsigned_decimal' => $h(new DecimalField($name, false, 39, 0, false), 'decimal(39,0) unsigned'),
            'signed_decimal' => $h(new DecimalField($name, false, 14, 4, true), 'decimal(18,4)'),

            $h(new BoolField($name, false), 'tinyint(1)'),
            $h(new BoolField($name, false), 'bit'),

            'binary' => $h(new BinaryField($name, false, 1), 'binary(1)'),
            'varbinary' => $h(new BinaryField($name, false, 4), 'varbinary(4)'),

            $h(new TextField($name, false), 'blob'),
            $h(new TextField($name, false), 'tinyblob'),
            $h(new TextField($name, false), 'smallblob'),
            $h(new TextField($name, false), 'mediumblob'),
            $h(new TextField($name, false), 'longblob'),

            'json' => $h(new JsonField($name, false), 'json'),

            'varchar' => $h(new TextField($name, false, 50), 'varchar(50)'),
            'char' => $h(new TextField($name, false, 2), 'char(2)'),

            $h(new TextField($name, false), 'text'),
            $h(new TextField($name, false), 'tinytext'),
            $h(new TextField($name, false), 'smalltext'),
            $h(new TextField($name, false), 'mediumtext'),
            $h(new TextField($name, false), 'longtext'),

            // TODO can have proper fields and generators in factories

            $h(new YearField($name, false, 4), 'year(4)'),

            $h(new TextField($name, false), 'time'),
            $h(new TextField($name, false), 'datetime'),
            $h(new DateField($name, true, DateField::MYSQL_TIMESTAMP), 'timestamp'),
            $h(new TextField($name, false), 'date'),
        ];
    }

    public function testGetIntLength_on_unknown_type()
    {
        $method = $this->getPrivateMethod($this->fieldResolver, 'getIntLength');
        $this->expectException(\InvalidArgumentException::class);
        $method->invokeArgs($this->fieldResolver, [self::randomString()]);
    }
}
