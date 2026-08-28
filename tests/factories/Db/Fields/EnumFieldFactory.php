<?php declare(strict_types = 1);

namespace kristijorgji\Tests\Factories\Db\Fields;

use kristijorgji\DbToPhp\Db\Fields\EnumField;
use kristijorgji\DbToPhp\Support\StringCollection;
use kristijorgji\Tests\Factories\BaseFactory;
use function rand;

class EnumFieldFactory extends BaseFactory
{
    public static function make() : EnumField
    {
        return new EnumField(
            self::faker()->text(20),
            self::faker()->boolean(),
            self::randomAllowedValues(),
        );
    }

    public static function randomAllowedValues() : StringCollection
    {
        $maxValues = 10;
        $allowedValues = [];

        for ($i = 0; $i < rand(3, $maxValues); $i++) {
            $allowedValues[] = self::randomString(rand(2, 10));
        }

        return new StringCollection(... $allowedValues);
    }
}
