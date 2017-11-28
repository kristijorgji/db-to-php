<?php

namespace kristijorgji\UnitTests\Db\Adapters\MySql;

use kristijorgji\DbToPhp\Db\Adapters\MySql\MySqlFieldResolver;
use kristijorgji\DbToPhp\Db\Fields\BinaryField;
use kristijorgji\DbToPhp\Db\Fields\BoolField;
use kristijorgji\DbToPhp\Db\Fields\DoubleField;
use kristijorgji\DbToPhp\Db\Fields\EnumField;
use kristijorgji\DbToPhp\Db\Fields\Field;
use kristijorgji\DbToPhp\Db\Fields\FloatField;
use kristijorgji\DbToPhp\Db\Fields\IntegerField;
use kristijorgji\DbToPhp\Db\Fields\TextField;
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

        $h = function (Field $field) use ($name) {
            return [
                $name, $field->getType(), ($field->isNullable() ? 'YES' : 'NO'), $field
            ];
        };

        return [
            'nullable' => $h(new TextField($name, 'char(1)', true, 1)),
            'not_nullable' => $h(new TextField($name, 'char(1)', false, 1)),
            'enum' => $h(
                new EnumField($name, 'enum(\'j,aru\',\'naru\',\'daru\')', false, new StringCollection(
                    ... ['j,aru', 'naru', 'daru']))
            ),

            'unsigned_int8' => $h(new IntegerField($name, 'tinyint(3) unsigned', false, 8, false)),
            'signed_int8' => $h(new IntegerField($name, 'tinyint(3)', false, 8, true)),

            'unsigned_int16' => $h(new IntegerField($name, 'smallint(5) unsigned', false, 16, false)),
            'signed_int16' => $h(new IntegerField($name, 'smallint(5)', false, 16, true)),

            'unsigned_int24' => $h(new IntegerField($name, 'mediumint(9) unsigned', false, 24, false)),
            'signed_int24' => $h(new IntegerField($name, 'mediumint(9)', false, 24, true)),

            'unsigned_int32' => $h(new IntegerField($name, 'int(11) unsigned', false, 32, false)),
            'signed_int32' => $h(new IntegerField($name, 'int(11)', false, 32, true)),

            'unsigned_int64' => $h(new IntegerField($name, 'bigint(20) unsigned', false, 64, false)),
            'signed_int64' => $h(new IntegerField($name, 'bigint(20)', false, 64, true)),

            $h(new FloatField($name, 'float', false)),

            $h(new DoubleField($name, 'double', false)),
            $h(new DoubleField($name, 'decimal(20)', false)),
            $h(new DoubleField($name, 'decimal(18,4)', false)),
            $h(new DoubleField($name, 'dec(18,4)', false)),
            $h(new DoubleField($name, 'real(10)', false)),

            $h(new BoolField($name, 'tinyint(1)', false)),
            $h(new BoolField($name, 'bit', false)),

            'binary' => $h(new BinaryField($name, 'binary(1)', false, 1)),
            'varbinary' => $h(new BinaryField($name, 'varbinary(4)', false, 4)),

            $h(new TextField($name, 'blob', false)),
            $h(new TextField($name, 'tinyblob', false)),
            $h(new TextField($name, 'smallblob', false)),
            $h(new TextField($name, 'mediumblob', false)),
            $h(new TextField($name, 'longblob', false)),

            $h(new EnumField($name, 'enum(\'1\',\'4\',\'111\')', false, new StringCollection(
                ... ['1', '4', '111']
            ))),

            'varchar' => $h(new TextField($name, 'varchar(50)', false, 50)),
            'char' => $h(new TextField($name, 'char(2)', false, 2)),

            $h(new TextField($name, 'text', false)),
            $h(new TextField($name, 'tinytext', false)),
            $h(new TextField($name, 'smalltext', false)),
            $h(new TextField($name, 'mediumtext', false)),
            $h(new TextField($name, 'longtext', false)),

            $h(new TextField($name, 'time', false)),
            $h(new TextField($name, 'timestamp', true)),
        ];
    }
}
