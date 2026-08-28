<?php declare(strict_types = 1);

namespace kristijorgji\Tests\Factories\Rules\Php;

use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;
use kristijorgji\Tests\Factories\BaseFactory;
use function array_rand;

class PhpTypeFactory extends BaseFactory
{
    public static function make() : PhpType
    {
        return new PhpType(self::makeType(), self::faker()->boolean());
    }

    public static function makeType() : PhpTypes
    {
        return PhpTypes::cases()[array_rand(PhpTypes::cases())];
    }
}
