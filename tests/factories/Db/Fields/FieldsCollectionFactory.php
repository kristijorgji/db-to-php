<?php declare(strict_types = 1);

namespace kristijorgji\Tests\Factories\Db\Fields;

use kristijorgji\DbToPhp\Db\Fields\FieldsCollection;
use function array_map;
use function range;

class FieldsCollectionFactory
{
    public static function make(int $size = 7) : FieldsCollection
    {
        return new FieldsCollection(... array_map(fn() => FieldFactory::make(), range(1, $size)));
    }
}
