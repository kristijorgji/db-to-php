<?php

namespace kristijorgji\DbToPhp\Data;

abstract class AbstractEntity
{
    /**
     * @var array
     */
    private $__original = [];

    public function __construct()
    {
        $this->sync();
    }

    /**
     * @param string $key
     * @param $value
     */
    protected function track(string $key, $value)
    {
        if (! array_key_exists($key, $this->__original)) {
            $this->__original[$key] = $value;
        }
    }

    /**
     * @return bool
     */
    public function isDirty() : bool
    {
        foreach ($this->__original as $key => $value) {
            if ($this->{$key} !== $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
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

    /**
     * @return void
     */
    public function sync()
    {
        foreach ($this as $key => $value) {
            if ($key !== '__original') {
                $this->__original[$key] = $value;
            }
        }
    }
}
