<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Data;

use function array_key_exists;
use function camelToSnakeCase;

abstract class AbstractEntity
{
    private array $__original = [];

    public function __construct()
    {
        $this->sync();
    }

    protected function track(string $key, mixed $value): void
    {
        if (! array_key_exists($key, $this->__original)) {
            $this->__original[$key] = $value;
        }
    }

    public function isDirty() : bool
    {
        foreach ($this->__original as $key => $value) {
            if ($this->{$key} !== $value) {
                return true;
            }
        }

        return false;
    }

    public function dirtyFields() : array
    {
        $dirty = [];

        foreach ($this->__original as $property => $originalValue) {
            $currentValue = $this->{$property};

            if ($currentValue !== $originalValue) {
                $dirty[camelToSnakeCase($property)] = $currentValue;
            }
        }

        return $dirty;
    }

    public function sync(): void
    {
        foreach ($this as $key => $value) {
            if ($key !== '__original') {
                $this->__original[$key] = $value;
            }
        }
    }
}
