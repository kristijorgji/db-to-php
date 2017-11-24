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
    public function getSampleProperty() : PhpProperty
    {
        return new PhpProperty(
            new PhpAccessModifiers(PhpAccessModifiers::PUBLIC),
            new PhpType(new PhpTypes(PhpTypes::INTEGER), true),
            'testProperty'
        );
    }

    /**
     * @return PhpPropertiesCollection
     */
    public function getSampleProperties() : PhpPropertiesCollection
    {
        return new PhpPropertiesCollection(... [
            new PhpProperty(
                new PhpAccessModifiers(PhpAccessModifiers::PUBLIC),
                new PhpType(new PhpTypes(PhpTypes::FLOAT), true),
                'salary'
            ),
            new PhpProperty(
                new PhpAccessModifiers(PhpAccessModifiers::PRIVATE),
                new PhpType(new PhpTypes(PhpTypes::BOOL), false),
                'active'
            ),
            new PhpProperty(
                new PhpAccessModifiers(PhpAccessModifiers::PROTECTED),
                new PhpType(new PhpTypes(PhpTypes::STRING), true),
                'name'
            ),
            new PhpProperty(
                new PhpAccessModifiers(PhpAccessModifiers::PRIVATE),
                new PhpType(new PhpTypes(PhpTypes::INTEGER), true),
                'year'
            )
        ]);
    }
}
