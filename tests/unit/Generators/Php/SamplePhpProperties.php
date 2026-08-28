<?php

namespace kristijorgji\UnitTests\Generators\Php;

use kristijorgji\DbToPhp\Rules\Php\PhpAccessModifiers;
use kristijorgji\DbToPhp\Rules\Php\PhpPropertiesCollection;
use kristijorgji\DbToPhp\Rules\Php\PhpProperty;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;

trait SamplePhpProperties
{
    /**
     * @return PhpProperty
     */
    public static function getSampleProperty() : PhpProperty
    {
        return new PhpProperty(
            PhpAccessModifiers::PUBLIC,
            new PhpType(PhpTypes::INTEGER, true),
            'testProperty'
        );
    }

    /**
     * @return PhpPropertiesCollection
     */
    public static function getSampleProperties() : PhpPropertiesCollection
    {
        return new PhpPropertiesCollection(... [
            new PhpProperty(
                PhpAccessModifiers::PUBLIC,
                new PhpType(PhpTypes::FLOAT, true),
                'salary'
            ),
            new PhpProperty(
                PhpAccessModifiers::PRIVATE,
                new PhpType(PhpTypes::BOOL, false),
                'active'
            ),
            new PhpProperty(
                PhpAccessModifiers::PROTECTED,
                new PhpType(PhpTypes::STRING, true),
                'name'
            ),
            new PhpProperty(
                PhpAccessModifiers::PRIVATE,
                new PhpType(PhpTypes::INTEGER, true),
                'year'
            )
        ]);
    }
}
