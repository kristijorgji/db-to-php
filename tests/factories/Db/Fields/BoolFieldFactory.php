<?php

namespace kristijorgji\Tests\Factories\Db\Fields;

use kristijorgji\DbToPhp\Db\Fields\BoolField;
use kristijorgji\Tests\Factories\BaseFactory;

class BoolFieldFactory extends BaseFactory
{
    /**
     * @return BoolField
     */
    public static function make() : BoolField
    {
        return new BoolField(
            self::faker()->text(20),
            self::faker()->text(20),
            self::faker()->boolean()
        );
    }
}
