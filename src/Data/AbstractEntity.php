<?php

namespace kristijorgji\DbToPhp\Data;

// TODO

abstract class AbstractEntity
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected $original = [];

    /**
     * @var bool
     */
    protected $booted = false;

    /**
     * @param string $key
     * @param $value
     */
    protected function set(string $key, $value)
    {
        if (! array_key_exists($key, $this->original)) {
            $this->original[$key] = $value;
        }

        $this->attributes[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     */
    protected function get(string $key)
    {
        return $this->attributes[$key];
    }

    /**
     * @return bool
     */
    public function isDirty() : bool
    {
        if (count($this->original) < count($this->attributes)) {
            return true;
        }

        foreach ($this->original as $key => $value) {
            if ($this->attributes[$key] !== $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getDirty() : array
    {
        $dirty = [];

        foreach ($this->original as $property => $originalValue) {
            $currentValue = $this->attributes[$property];

            if ($currentValue !== $originalValue) {
                $dirty[camelToSnakeCase($property)] = $currentValue;
            }
        }

        return $dirty;
    }

    public function completeBuild()
    {

    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->original = [];
    }
}
