<?php declare(strict_types = 1);

namespace kristijorgji\Tests\Factories\Db\Fields;

use kristijorgji\DbToPhp\Db\Fields\TextField;
use kristijorgji\Tests\Factories\BaseFactory;
use function random_int;

class TextFieldFactory extends BaseFactory
{
    public static function make() : TextField
    {
        return new TextField(
            self::faker()->text(20),
            self::faker()->boolean(),
            random_int(16, 128),
        );
    }
}
