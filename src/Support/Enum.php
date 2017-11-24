<?php

namespace kristijorgji\DbToPhp\Support;

use ReflectionClass;

abstract class Enum
{
    /**
     * @var bool
     */
    private static $initiated = [];

    /**
     * @var array
     */
    private static $options = [];

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    final public function __construct(string $value)
    {
        self::initIfNot();

        $validValue = false;
        foreach (self::$options[static::class] as $allowedValue) {
            if ($value === $allowedValue) {
                $validValue = true;
                break;
            }
        }

        if (!$validValue) {
            throw new \InvalidArgumentException(
                sprintf('The provided value "%s" is invalid for enum class %s', $value, static::class)
            );
        }

        $this->value = (string) $value;
    }

    /**
     * @return string
     */
    final public function __toString() : string
    {
        return $this->value;
    }

    /**
     * @return string
     * @throws \Exception
     */
    final public function getSelfKey() : string
    {
        foreach (self::$options[static::class] as $key => $value) {
            if ($this->value === $value) {
                return $key;
            }
        }

        throw new \Exception(sprintf('Value %s has no corresponding key', $this->value));
    }

    /**
     * @param $needleValue
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function getKey($needleValue) : string
    {
        self::initIfNot();
        foreach (self::$options[static::class] as $key => $value) {
            if ($needleValue === $value) {
                return $key;
            }
        }

        throw new \InvalidArgumentException(sprintf('Value %s has no corresponding name', $needleValue));
    }

    /**
     * @return array
     */
    public static function toArray() : array
    {
        self::initIfNot();
        return self::$options[static::class];
    }

    /**
     * @return array
     */
    public static function getValues() : array
    {
        self::initIfNot();
        return array_values(self::$options[static::class]);
    }

    /**
     * @return void
     */
    private static function initIfNot() : void
    {
        if (isset(self::$initiated[static::class])) {
            return;
        }

        $reflector = new ReflectionClass(static::class);
        self::$options[static::class] = $reflector->getConstants();
        self::$initiated[static::class] = true;
    }
}
