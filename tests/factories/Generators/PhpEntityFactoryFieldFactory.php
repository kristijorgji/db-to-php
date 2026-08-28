<?php declare(strict_types = 1);

namespace kristijorgji\Tests\Factories\Generators;

use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryField;
use kristijorgji\Tests\Factories\BaseFactory;
use function count;
use function rand;

class PhpEntityFactoryFieldFactory extends BaseFactory
{
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
            'self::randomString()',
        ];

        return new PhpEntityFactoryField(
            self::faker()->text(20),
            $resolvers[rand(0, count($resolvers) - 1)],
        );
    }
}
