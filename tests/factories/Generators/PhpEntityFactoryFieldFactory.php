<?php

namespace kristijorgji\Tests\Factories\Generators;

use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryField;
use kristijorgji\Tests\Factories\BaseFactory;
use kristijorgji\Tests\Factories\Db\Fields\FieldFactory;
use kristijorgji\Tests\Factories\Rules\Php\PhpTypeFactory;

class PhpEntityFactoryFieldFactory extends BaseFactory
{
    /**
     * @return PhpEntityFactoryField
     */
    public static function make() : PhpEntityFactoryField
    {
        $resolvers = [
            'self::randomInt32()',
            'self::randomInt8()',
            'self::randomArray',
            'self::randomString()'
        ];

        return new PhpEntityFactoryField(
            self::faker()->text(20),
            self::faker()->text(20),
            PhpTypeFactory::make(),
            $resolvers[rand(0, count($resolvers) - 1)]
        );
    }
}
