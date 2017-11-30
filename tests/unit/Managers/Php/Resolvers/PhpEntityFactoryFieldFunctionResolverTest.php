<?php

namespace kristijorgji\UnitTests\Managers\Php\Resolvers;

use kristijorgji\DbToPhp\Db\Fields\BinaryField;
use kristijorgji\DbToPhp\Db\Fields\BoolField;
use kristijorgji\DbToPhp\Db\Fields\DecimalField;
use kristijorgji\DbToPhp\Db\Fields\DoubleField;
use kristijorgji\DbToPhp\Db\Fields\EnumField;
use kristijorgji\DbToPhp\Db\Fields\Field;
use kristijorgji\DbToPhp\Db\Fields\FloatField;
use kristijorgji\DbToPhp\Db\Fields\IntegerField;
use kristijorgji\DbToPhp\Db\Fields\TextField;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;
use kristijorgji\DbToPhp\Support\StringCollection;
use kristijorgji\DbToPhp\Managers\Php\Resolvers\PhpEntityFactoryFieldFunctionResolver;
use kristijorgji\Tests\Helpers\TestCase;

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
     * @param PhpType $type
     * @param string $expected
     */
    public function testResolve(Field $field, PhpType $type, string $expected)
    {
        $actual = $this->resolver->resolve($field, $type);
        $this->assertEquals($expected, $actual);
    }

    public function testResolve_invalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->resolver->resolve(
            new BinaryField(self::randomString(), self::randomString(), false, 123),
            new PhpType(new PhpTypes(PhpTypes::ARRAY), false)
        );
    }

    public function resolveProvider()
    {
        $name = self::randomString();
        $type = self::randomString();

        return [
            'binary' => [
                new BinaryField($name, $type, false, 123),
                new PhpType(new PhpTypes(PhpTypes::STRING), false),
                'self::randomString(rand(0, 123))'
            ],

            'boolean' => [
                new BoolField($name, $type, false),
                new PhpType(new PhpTypes(PhpTypes::BOOL), false),
                'self::randomBoolean()'
            ],

            'double' => [
                new DoubleField($name, $type, false),
                new PhpType(new PhpTypes(PhpTypes::FLOAT), false),
                'self::randomFloat()'
            ],

            'enum' => [
                new EnumField($name, $type, false, new StringCollection(... ['test', 'dada', 'p'])),
                new PhpType(new PhpTypes(PhpTypes::STRING), false),
                'self::chooseRandomString(\'test\', \'dada\', \'p\')'
            ],

            'enum_with_quote' => [
                new EnumField($name, $type, false, new StringCollection(... ['t\'est', 'dada', 'p'])),
                new PhpType(new PhpTypes(PhpTypes::STRING), false),
                'self::chooseRandomString(\'t\\\'est\', \'dada\', \'p\')'
            ],

            'float' => [
                new FloatField($name, $type, false),
                new PhpType(new PhpTypes(PhpTypes::FLOAT), false),
                'self::randomFloat()'
            ],

            'int_7_signed' => [
                new IntegerField($name, $type, true, 7, true),
                new PhpType(new PhpTypes(PhpTypes::INTEGER), true),
                'self::randomInt32()'
            ],

            'int_8_signed' => [
                new IntegerField($name, $type, true, 8, true),
                new PhpType(new PhpTypes(PhpTypes::INTEGER), true),
                'self::randomInt8()'
            ],
            'int_8_unsigned' => [
                new IntegerField($name, $type, true, 8, false),
                new PhpType(new PhpTypes(PhpTypes::INTEGER), true),
                'self::randomUnsignedInt8()'
            ],
            'int_16_unsigned' => [
                new IntegerField($name, $type, true, 16, false),
                new PhpType(new PhpTypes(PhpTypes::INTEGER), true),
                'self::randomUnsignedInt16()'
            ],
            'int_24_unsigned' => [
                new IntegerField($name, $type, true, 24, false),
                new PhpType(new PhpTypes(PhpTypes::INTEGER), true),
                'self::randomUnsignedInt24()'
            ],
            'int_32_unsigned' => [
                new IntegerField($name, $type, true, 32, false),
                new PhpType(new PhpTypes(PhpTypes::INTEGER), true),
                'self::randomUnsignedInt32()'
            ],
            'int_64_unsigned' => [
                new IntegerField($name, $type, true, 64, false),
                new PhpType(new PhpTypes(PhpTypes::INTEGER), true),
                'self::randomUnsignedInt64()'
            ],

            'text' => [
                new TextField($name, $type, false, 188),
                new PhpType(new PhpTypes(PhpTypes::STRING), false),
                'self::randomString(rand(0, 188))'
            ],

            'decimal_no_fractional_part' => [
                new DecimalField($name, $type, false, 4),
                new PhpType(new PhpTypes(PhpTypes::INTEGER), false),
                'self::randomNumber(4, true)'
            ],

            'decimal_with_fractional_part' => [
                new DecimalField($name, $type, false, 8, 4),
                new PhpType(new PhpTypes(PhpTypes::INTEGER), false),
                'self::randomFloat(4)'
            ]
        ];
    }
}
