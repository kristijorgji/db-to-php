<?php

namespace kristijorgji\Tests\Factories\Db\Fields;

use kristijorgji\DbToPhp\Db\Fields\BinaryField;
use kristijorgji\Tests\Factories\BaseFactory;

class BinaryFieldFactory extends BaseFactory
{
    /**
     * @return BinaryField
     */
    public static function make() : BinaryField
    {
        return new BinaryField(
            self::faker()->text(20),
            self::faker()->boolean(),
            random_int(16, 128)
        );
    }
}
