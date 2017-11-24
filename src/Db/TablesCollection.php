<?php

namespace kristijorgji\DbToPhp\Db;

use Traversable;

class TablesCollection implements \IteratorAggregate
{
    /**
     * @var Table[]
     */
    private $tables = [];

    /**
     * @param Table[] $fields
     */
    public function __construct(Table... $fields)
    {
        $this->tables = $fields;
    }

    /**
     * @return Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->tables);
    }

    /**
     * @return Table[]
     */
    public function all() : array
    {
        return $this->tables;
    }

    /**
     * @param int $index
     * @return Table
     */
    public function getAt(int $index) : Table
    {
        return $this->tables[$index];
    }
}
