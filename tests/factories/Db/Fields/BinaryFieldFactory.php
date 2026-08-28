<?php declare(strict_types = 1);

namespace kristijorgji\Tests\Factories\Db\Fields;

use kristijorgji\DbToPhp\Db\Fields\BinaryField;
use kristijorgji\Tests\Factories\BaseFactory;
use function random_int;

class BinaryFieldFactory extends BaseFactory
{
    public static function make() : BinaryField
    {
        return new BinaryField(
            self::faker()->text(20),
            self::faker()->boolean(),
            random_int(16, 128),
        );
    }
}
