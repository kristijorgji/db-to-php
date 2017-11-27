<?php

namespace kristijorgji\Tests\Factories\Db\Fields;

use kristijorgji\DbToPhp\Db\Fields\IntegerField;
use kristijorgji\Tests\Factories\BaseFactory;

class IntegerFieldFactory extends BaseFactory
{
    /**
     * @return IntegerField
     */
    public static function make() : IntegerField
    {
        return new IntegerField(
            self::faker()->text(20),
            self::faker()->text(20),
            self::faker()->boolean(),
            random_int(16, 128),
            self::faker()->boolean()
        );
    }
}
