<?php

namespace kristijorgji\Tests\Factories\Db;

use kristijorgji\DbToPhp\Db\Table;
use kristijorgji\Tests\Factories\BaseFactory;

class TableFactory extends BaseFactory
{
    /**
     * @return Table
     */
    public static function make() : Table
    {
        return new Table(
            self::randomString()
        );
    }
}
