<?php

namespace kristijorgji\Tests\Factories\Generators;

use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryFieldsCollection;

class PhpEntityFactoryFieldsCollectionFactory
{
    /**
     * @param int $size
     * @return PhpEntityFactoryFieldsCollection
     */
    public static function make(int $size = 7) : PhpEntityFactoryFieldsCollection
    {
        return new PhpEntityFactoryFieldsCollection(... array_map(function () {
            return PhpEntityFactoryFieldFactory::make();
        }, range(1, $size)));
    }
}
