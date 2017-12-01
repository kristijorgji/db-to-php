<?php

namespace kristijorgji\Tests\Factories\Db\Fields;

use kristijorgji\DbToPhp\Db\Fields\DoubleField;
use kristijorgji\Tests\Factories\BaseFactory;

class DoubleFieldFactory extends BaseFactory
{
    /**
     * @return DoubleField()
     */
    public static function make() : DoubleField
    {
        return new DoubleField(
            self::faker()->text(20),
            self::faker()->text(20),
            self::faker()->boolean()
        );
    }
}
