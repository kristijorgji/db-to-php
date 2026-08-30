<?php declare(strict_types = 1);

namespace kristijorgji\Tests\Helpers;

use Faker\Factory;
use Faker\Generator;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use function base64_encode;
use function explode;
use function fclose;
use function fgets;
use function fopen;
use function ltrim;
use function preg_match;
use function random_bytes;
use function str_replace;
use function strlen;
use function substr;
use const PHP_EOL;

trait TestHelpers
{
    protected static ?Generator $faker = null;

    public static function faker() : Generator
    {
        if (self::$faker !== null) {
            return self::$faker;
        }

        return Factory::create();
    }

    public static function uniqueUnsignedByte() : int
    {
        $unsignedInt8Max =  255;
        return self::faker()->unique()->numberBetween(0, $unsignedInt8Max);
    }

    public static function uniqueUnsignedInt16() : int
    {
        $unsignedInt16Max =  65535;
        return self::faker()->unique()->numberBetween(0, $unsignedInt16Max);
    }

    public static function uniqueUnsignedInt32() : int
    {
        $unsignedInt32Max =  4294967295;
        return self::faker()->unique()->numberBetween(0, $unsignedInt32Max);
    }

    public static function uniqueInt32() : int
    {
        $int32Max = 2147483647;
        return self::faker()->unique()->numberBetween(0, $int32Max);
    }

    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     */
    public static function randomString(int $length = 16): string
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

    public static function setPrivateProperty(object $instance, string $propertyName, mixed $value): void
    {
        $property = self::getPrivateProperty($instance::class, $propertyName);
        $property->setValue($instance, $value);
    }

    /**
     * getPrivateProperty
     *
     */
    public static function getPrivateProperty(string $className, string $propertyName): ReflectionProperty
    {
        $reflector = new ReflectionClass($className);
        return $reflector->getProperty($propertyName);
    }

    public static function getPrivateMethod(object|string $instance, string $name): ReflectionMethod
    {
        $class = new ReflectionClass($instance);
        return $class->getMethod($name);
    }

    /**
     * @return array<string>
     */
    public static function getMethodAnnotations(object|string $instance, string $name) : array
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

    public static function objectToArray(object $object): array
    {
        $reflectionClass = new ReflectionClass($object::class);
        $array = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $array[$property->getName()] = $property->getValue($object);
        }
        return $array;
    }

    public static function baseTestsPath(?string $path = null) : string
    {
        $basePath = __DIR__ . '/../';

        if ($path !== null) {
            return $basePath . '/' . $path;
        }

        return $basePath;
    }

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
