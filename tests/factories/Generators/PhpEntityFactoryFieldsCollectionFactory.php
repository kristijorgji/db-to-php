<?php declare(strict_types = 1);

namespace kristijorgji\Tests\Factories\Generators;

use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryFieldsCollection;
use function array_map;
use function range;

class PhpEntityFactoryFieldsCollectionFactory
{
    public static function make(int $size = 7) : PhpEntityFactoryFieldsCollection
    {
        return new PhpEntityFactoryFieldsCollection(
            ... array_map(fn() => PhpEntityFactoryFieldFactory::make(), range(1, $size)),
        );
    }
}
