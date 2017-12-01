<?php

namespace kristijorgji\Tests\Factories\Db\Fields;

use kristijorgji\DbToPhp\Db\Fields\FloatField;
use kristijorgji\Tests\Factories\BaseFactory;

class FloatFieldFactory extends BaseFactory
{
    /**
     * @return FloatField
     */
    public static function make() : FloatField
    {
        return new FloatField(
            self::faker()->text(20),
            self::faker()->text(20),
            self::faker()->boolean()
        );
    }
}
