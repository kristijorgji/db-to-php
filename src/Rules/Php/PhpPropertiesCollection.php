<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Rules\Php;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class PhpPropertiesCollection implements IteratorAggregate
{
    /**
     * @var array<PhpProperty>
     */
    private array $properties = [];

    /**
     * @param PhpProperty<PhpProperty> $properties
     */
    public function __construct(PhpProperty ... $properties)
    {
        $this->properties = $properties;
    }

    /**
     * @return Traversable
     */
    public function getIterator() : Traversable
    {
        return new ArrayIterator($this->properties);
    }

    /**
     * @return array<PhpProperty>
     */
    public function all() : array
    {
        return $this->properties;
    }
}
