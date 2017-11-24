<?php

namespace kristijorgji\DbToPhp\Rules\Php;

use Traversable;

class PhpPropertiesCollection implements \IteratorAggregate
{
    /**
     * @var PhpProperty[]
     */
    private $properties = [];

    /**
     * @param PhpProperty[] $properties
     */
    public function __construct(PhpProperty... $properties)
    {
        $this->properties = $properties;
    }

    /**
     * @return Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->properties);
    }

    /**
     * @return PhpProperty[]
     */
    public function all() : array
    {
        return $this->properties;
    }
}
