<?php

namespace kristijorgji\Tests\Factories\Generators;

use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryField;
use kristijorgji\Tests\Factories\BaseFactory;
use kristijorgji\Tests\Factories\Rules\Php\PhpTypeFactory;

class PhpEntityFactoryFieldFactory extends BaseFactory
{
    /**
     * @return PhpEntityFactoryField
     */
    public static function make() : PhpEntityFactoryField
    {
        $resolvers = [
            'self::randomInt8()',
            'self::randomInt16()',
            'self::randomInt24()',
            'self::randomInt32()',
            'self::randomInt64()',
            'self::randomUnsignedInt8()',
            'self::randomUnsignedInt16()',
            'self::randomUnsignedInt24()',
            'self::randomUnsignedInt32()',
            'self::randomUnsignedInt64()',
            'self::randomArray',
            'self::randomString()'
        ];

        return new PhpEntityFactoryField(
            self::faker()->text(20),
            $resolvers[rand(0, count($resolvers) - 1)]
        );
    }
}
