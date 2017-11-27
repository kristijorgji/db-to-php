<?php

namespace kristijorgji\Tests\Factories\Db\Fields;

use kristijorgji\DbToPhp\Db\Fields\Field;
use kristijorgji\Tests\Factories\BaseFactory;

abstract class FieldFactory extends BaseFactory
{
    /**
     * @var array
     */
    private static $factories = [
        BoolFieldFactory::class,
        TextFieldFactory::class,
        BinaryFieldFactory::class,
        IntegerFieldFactory::class,
        DoubleFieldFactory::class,
        FloatFieldFactory::class,
        EnumFieldFactory::class
    ];

    /**
     * @return Field
     */
    public static function make() : Field
    {
        return self::$factories[rand(0, count(self::$factories) - 1)]::make();
    }
}
