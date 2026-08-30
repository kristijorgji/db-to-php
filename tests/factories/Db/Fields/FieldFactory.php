<?php declare(strict_types = 1);

namespace kristijorgji\Tests\Factories\Db\Fields;

use kristijorgji\DbToPhp\Db\Fields\Field;
use kristijorgji\Tests\Factories\BaseFactory;
use function count;
use function random_int;

abstract class FieldFactory extends BaseFactory
{
    private static array $factories = [
        BoolFieldFactory::class,
        TextFieldFactory::class,
        BinaryFieldFactory::class,
        IntegerFieldFactory::class,
        DoubleFieldFactory::class,
        FloatFieldFactory::class,
        EnumFieldFactory::class,
    ];

    public static function make() : Field
    {
        return self::$factories[random_int(0, count(self::$factories) - 1)]::make();
    }
}
