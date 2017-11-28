<?php

namespace kristijorgji\Tests\Helpers;

use DirectoryIterator;
use Faker\Factory;
use Faker\Generator;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

trait TestHelpers
{
    /**
     * @var Generator
     */
    protected static $faker;

    /**
     * @return Generator
     */
    public static function faker() : Generator
    {
        if (self::$faker != null) {
            return self::$faker;
        }

        return Factory::create();
    }

    /**
     * @return int
     */
    public static function uniqueUnsignedByte() : int
    {
        $unsignedInt8Max =  255;
        return self::faker()->unique()->numberBetween(0, $unsignedInt8Max);
    }

    /**
     * @return int
     */
    public static function uniqueUnsignedInt16() : int
    {
        $unsignedInt16Max =  65535;
        return self::faker()->unique()->numberBetween(0, $unsignedInt16Max);
    }

    /**
     * @return int
     */
    public static function uniqueUnsignedInt32() : int
    {
        $unsignedInt32Max =  4294967295;
        return self::faker()->unique()->numberBetween(0, $unsignedInt32Max);
    }

    /**
     * @return int
     */
    public static function uniqueInt32() : int
    {
        $int32Max = 2147483647;
        return self::faker()->unique()->numberBetween(0, $int32Max);
    }

    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * @param  int  $length
     * @return string
     */
    public static function randomString($length = 16)
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

    /**
     * @param $instance
     * @param string $propertyName
     * @param $value
     */
    public static function setPrivateProperty($instance, string $propertyName, $value)
    {
        $property = self::getPrivateProperty(get_class($instance), $propertyName);
        $property->setValue($instance, $value);
    }

    /**
     * getPrivateProperty
     *
     * @param   string $className
     * @param   string $propertyName
     * @return  ReflectionProperty
     */
    public static function getPrivateProperty($className, $propertyName)
    {
        $reflector = new ReflectionClass($className);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * @param $instance
     * @param $name
     * @return ReflectionMethod
     */
    public static function getPrivateMethod($instance, $name)
    {
        $class = new ReflectionClass($instance);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    /**
     * @param $instance
     * @param $name
     * @return string[]
     */
    public static function getMethodAnnotations($instance, $name) : array
    {
        $class = new ReflectionClass($instance);
        $method = $class->getMethod($name);

        $processedAnnotations = [];

        $annotations = explode(PHP_EOL, $method->getDocComment());
        foreach ($annotations as $annotation) {
            if (!preg_match('#@([\w\d]+)(.*)#', $annotation, $matches)) {
                continue;
            }

            $processedAnnotations[$matches[1]] = ltrim($matches[2]);
        }

        return $processedAnnotations;
    }

    /**
     * @param $object
     * @return array
     */
    public static function objectToArray($object)
    {
        $reflectionClass = new ReflectionClass(get_class($object));
        $array = array();
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($object);
            $property->setAccessible(false);
        }
        return $array;
    }

    /**
     * @param string|null $path
     * @return string
     */
    public static function baseTestsPath(?string $path = null) : string
    {
        $basePath = __DIR__ . '/../';

        if ($path !== null) {
            return $basePath . '/' . $path;
        }

        return $basePath;
    }

    /**
     * @param string $path
     * @return array
     */
    public static function getExpected(string $path) : array
    {
        $handle = fopen($path, 'r');
        $captured = '';
        $groupBeingCaptured = null;
        $expected = [];

        while (($line = fgets($handle)) !== false) {
            if (preg_match('&^##\[(.+)]##$&', $line, $matches)) {
                if ($groupBeingCaptured !== null && $matches[1] !== $groupBeingCaptured) {
                    $expected[$groupBeingCaptured] = substr($captured, 0, strlen($captured) - 1);
                    $captured = '';
                }

                $groupBeingCaptured = $matches[1];

                continue;
            }

            $captured .= $line;
        }

        $expected[$groupBeingCaptured] = substr($captured, 0, strlen($captured) - 1);

        fclose($handle);
        return $expected;
    }
}
