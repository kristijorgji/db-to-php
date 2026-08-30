<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Db;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, Table>
 */
class TablesCollection implements IteratorAggregate
{
    /**
     * @var array<Table>
     */
    private array $tables;

    public function __construct(Table ... $fields)
    {
        $this->tables = $fields;
    }

    /**
     * @return ArrayIterator<int, Table>
     */
    public function getIterator() : Traversable
    {
        return new ArrayIterator($this->tables);
    }

    /**
     * @return array<Table>
     */
    public function all() : array
    {
        return $this->tables;
    }

    public function getAt(int $index) : Table
    {
        return $this->tables[$index];
    }
}
