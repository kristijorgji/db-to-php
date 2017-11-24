<?php

namespace kristijorgji\Tests\Factories\Db;

use kristijorgji\DbToPhp\Db\TablesCollection;

class TablesCollectionFactory
{
    /**
     * @param int $size
     * @return TablesCollection
     */
    public static function make(int $size = 7) : TablesCollection
    {
        return new TablesCollection(... array_map(function () {
            return TableFactory::make();
        }, range(1, $size)));
    }
}
