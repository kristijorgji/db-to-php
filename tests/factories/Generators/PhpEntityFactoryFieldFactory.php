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
        $lengths = [8, 16, 32, 64];

        return new PhpEntityFactoryField(
            self::faker()->text(20),
            self::faker()->text(20),
            PhpTypeFactory::make(),
            rand(0, 1) === true ? null : $lengths[rand(0, count($lengths) - 1)],
            rand(0, 1) === true ? null : (self::faker()->boolean)
        );
    }
}
