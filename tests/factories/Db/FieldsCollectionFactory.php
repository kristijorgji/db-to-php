<?php

namespace kristijorgji\Tests\Factories\Db;

use kristijorgji\DbToPhp\Db\FieldsCollection;

class FieldsCollectionFactory
{
    /**
     * @param int $size
     * @return FieldsCollection
     */
    public static function make(int $size = 7) : FieldsCollection
    {
        return new FieldsCollection(... array_map(function () {
            return FieldFactory::make();
        }, range(1, $size)));
    }

}
