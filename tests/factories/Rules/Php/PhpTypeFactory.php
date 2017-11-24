<?php

namespace kristijorgji\Tests\Factories\Rules\Php;

use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;
use kristijorgji\Tests\Factories\BaseFactory;

class PhpTypeFactory extends BaseFactory
{
    /**
     * @return PhpType
     */
    public static function make() : PhpType
    {
        return new PhpType(self::makeType(), self::faker()->boolean());
    }

    /**
     * @return PhpTypes
     */
    public static function makeType() : PhpTypes
    {
        return new PhpTypes(PhpTypes::getValues()[rand(0, count(PhpTypes::getValues()) - 1)]);
    }
}
