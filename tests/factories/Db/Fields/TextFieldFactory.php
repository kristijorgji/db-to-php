<?php

namespace kristijorgji\Tests\Factories\Db\Fields;

use kristijorgji\DbToPhp\Db\Fields\TextField;
use kristijorgji\Tests\Factories\BaseFactory;

class TextFieldFactory extends BaseFactory
{
    /**
     * @return TextField
     */
    public static function make() : TextField
    {
        return new TextField(
            self::faker()->text(20),
            self::faker()->boolean(),
            random_int(16, 128)
        );
    }
}
