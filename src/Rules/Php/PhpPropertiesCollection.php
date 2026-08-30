<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Rules\Php;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int|string, PhpProperty>
 */
class PhpPropertiesCollection implements IteratorAggregate
{
    /**
     * @var array<PhpProperty>
     */
    private readonly array $properties;

    public function __construct(PhpProperty ... $properties)
    {
        $this->properties = $properties;
    }

    /**
     * @return ArrayIterator<int|string, PhpProperty>
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
