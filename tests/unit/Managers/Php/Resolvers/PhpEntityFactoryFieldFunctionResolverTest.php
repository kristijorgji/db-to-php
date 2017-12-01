<?php

namespace kristijorgji\UnitTests\Managers\Php\Resolvers;

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
use kristijorgji\DbToPhp\Support\StringCollection;
use kristijorgji\DbToPhp\Managers\Php\Resolvers\PhpEntityFactoryFieldFunctionResolver;
use kristijorgji\Tests\Helpers\TestCase;
use kristijorgji\UnitTests\Mappers\Types\Php\TestDbField;

class PhpEntityFactoryFieldFunctionResolverTest extends TestCase
{
    /**
     * @var PhpEntityFactoryFieldFunctionResolver
     */
    private $resolver;

    public function setUp()
    {
        $this->resolver = new PhpEntityFactoryFieldFunctionResolver();
    }

    /**
     * @dataProvider resolveProvider
     * @param Field $field
     * @param string $expected
     */
    public function testResolve(Field $field, string $expected)
    {
        $actual = $this->resolver->resolve($field);
        $this->assertEquals($expected, $actual);
    }

    public function testResolve_invalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->resolver->resolve(
            new TestDbField(self::randomString(), false)
        );
    }

    public function resolveProvider()
    {
        $name = self::randomString();

        return [
            'binary' => [
                new BinaryField($name, false, 123),
                'self::randomString(rand(0, 123))'
            ],

            'boolean' => [
                new BoolField($name, false),
                'self::randomBoolean()'
            ],

            'double' => [
                new DoubleField($name, false),
                'self::randomFloat()'
            ],

            'enum' => [
                new EnumField($name, false, new StringCollection(... ['test', 'dada', 'p'])),
                'self::chooseRandomString(\'test\', \'dada\', \'p\')'
            ],

            'enum_with_quote' => [
                new EnumField($name, false, new StringCollection(... ['t\'est', 'dada', 'p'])),
                'self::chooseRandomString(\'t\\\'est\', \'dada\', \'p\')'
            ],

            'float' => [
                new FloatField($name, false),
                'self::randomFloat()'
            ],

            'int_7_signed' => [
                new IntegerField($name, true, 7, true),
                'self::randomInt32()'
            ],

            'int_8_signed' => [
                new IntegerField($name, true, 8, true),
                'self::randomInt8()'
            ],
            'int_8_unsigned' => [
                new IntegerField($name, true, 8, false),
                'self::randomUnsignedInt8()'
            ],
            'int_16_unsigned' => [
                new IntegerField($name, true, 16, false),
                'self::randomUnsignedInt16()'
            ],
            'int_24_unsigned' => [
                new IntegerField($name, true, 24, false),
                'self::randomUnsignedInt24()'
            ],
            'int_32_unsigned' => [
                new IntegerField($name, true, 32, false),
                'self::randomUnsignedInt32()'
            ],
            'int_64_unsigned' => [
                new IntegerField($name, true, 64, false),
                'self::randomUnsignedInt64()'
            ],

            'text' => [
                new TextField($name, false, 188),
                'self::randomString(rand(0, 188))'
            ],

            'json' => [
                new JsonField($name, false),
                'self::randomJson()'
            ],

            'date' => [
                new DateField($name, false, DateField::MYSQL_TIMESTAMP),
                'self::randomDate(\'Y-m-d H:i:s\')'
            ],

            'unsigned_decimal_no_fractional_part' => [
                new DecimalField($name, false, 4),
                'self::randomUnsignedNumber(4)'
            ],

            'unsigned_decimal_with_fractional_part' => [
                new DecimalField($name, false, 8, 7),
                'self::randomFloat(7)'
            ],

            'signed_decimal_no_fractional_part' => [
                new DecimalField($name, false, 4, 0, true),
                'self::randomNumber(4)'
            ],

            'signed_decimal_with_fractional_part' => [
                new DecimalField($name, false, 8, 7, true),
                'self::randomFloat(7)'
            ]
        ];
    }
}
