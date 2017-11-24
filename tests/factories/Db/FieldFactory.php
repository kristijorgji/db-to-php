<?php

namespace kristijorgji\Tests\Factories\Db;

use kristijorgji\DbToPhp\Db\Field;
use kristijorgji\Tests\Factories\BaseFactory;

class FieldFactory extends BaseFactory
{
    /**
     * @return Field
     */
    public static function make() : Field
    {
        return new Field(
            self::faker()->text(20),
            self::faker()->text(20),
            self::faker()->boolean()
        );
    }
}
