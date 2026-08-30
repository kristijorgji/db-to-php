<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Support;

use function count;

class StringCollection
{
    /**
     * @var array<string>
     */
    private array $elements = [];

    public function __construct(string ... $fields)
    {
        $this->elements = $fields;
    }

    /**
     * @return array<string>
     */
    public function all() : array
    {
        return $this->elements;
    }

    public function count() : int
    {
        return count($this->elements);
    }
}
