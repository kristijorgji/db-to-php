<?php declare(strict_types = 1);

namespace kristijorgji\Tests\Factories\Db;

use kristijorgji\DbToPhp\Db\TablesCollection;
use function array_map;
use function range;

class TablesCollectionFactory
{
    public static function make(int $size = 7) : TablesCollection
    {
        return new TablesCollection(... array_map(fn() => TableFactory::make(), range(1, $size)));
    }
}
