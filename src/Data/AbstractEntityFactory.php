<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Data;

use InvalidArgumentException;
use kristijorgji\DbToPhp\Data\Exceptions\InvalidEntityFactoryFieldException;
use function array_keys;
use function base64_encode;
use function count;
use function date;
use function implode;
use function in_array;
use function json_encode;
use function mt_getrandmax;
use function mt_rand;
use function pow;
use function rand;
use function random_bytes;
use function round;
use function snakeToPascalCase;
use function sprintf;
use function str_replace;
use function strlen;
use function substr;
use function time;
use const PHP_EOL;
use const PHP_INT_MAX;
use const PHP_INT_MIN;

abstract class AbstractEntityFactory
{
    protected static array $fields = [];

    /**
     * @throws InvalidEntityFactoryFieldException
     */
    public static function validateData(array $data): void
    {
        foreach (array_keys($data) as $key) {
            if (!in_array($key, static::$fields)) {
                throw new InvalidEntityFactoryFieldException(
                    sprintf(
                        'The given key: %s in the data array is not a valid key.%sAvailable keys are: (%s)',
                        $key,
                        PHP_EOL,
                        implode(', ', static::$fields),
                    ),
                );
            }
        }
    }

    public static function mapArrayToEntity(array $data, string $toClass): mixed
    {
        $item  = new $toClass;

        foreach ($data as $key => $value) {
            $item->{'set' . snakeToPascalCase($key)}($value);
        }

        return $item;
    }

    public static function randomArray() : array
    {
        return [
            self::randomString() => self::randomInt32(),
        ];
    }

    public static function randomJson() : string
    {
        return json_encode(self::randomArray());
    }

    public static function randomBoolean(int $chanceOfGettingTrue = 50) : bool
    {
        return mt_rand(1, 100) <= $chanceOfGettingTrue;
    }

    public static function randomDate(string $format = 'Y-m-d H:i:s') : string
    {
        $now = time();
        return date($format, $now - self::randomUnsignedNumber(strlen((string) $now) - 1));
    }

    public static function randomInt8() : int
    {
        return rand(-128, 127);
    }

    public static function randomUnsignedInt8() : int
    {
        return rand(0, 255);
    }

    public static function randomInt16() : int
    {
        return rand(-32768, 32767);
    }

    public static function randomUnsignedInt16() : int
    {
        return rand(0, 65535);
    }

    public static function randomInt24() : int
    {
        return rand(-8388608, 8388607);
    }

    public static function randomUnsignedInt24() : int
    {
        return rand(0, 16777215);
    }

    public static function randomInt32() : int
    {
        return rand(-2147483648, 2147483647);
    }

    public static function randomUnsignedInt32() : int
    {
        return rand(0, 4294967295);
    }

    public static function randomInt64() : int
    {
        return rand(PHP_INT_MIN, PHP_INT_MAX);
    }

    /**
     * PHP max supported int is signed 64 bit integer
     * that's why in this case I return again an unsigned 32 bit int
     * which still is also a unsigned 64 bit int
     *
     */
    public static function randomUnsignedInt64() : int
    {
        return self::randomUnsignedInt32();
    }

    public static function randomYear(int $digits) : int
    {
        return self::randomUnsignedNumber($digits, true);
    }

    /**
     * Return a random float number
     *
     * @example 48.8932
     *
     */
    public static function randomFloat(
        ?int $nbMaxDecimals = null,
        int|float $min = 0,
        int|float|null $max = null,
    ) : float {
        if ($nbMaxDecimals === null) {
            $nbMaxDecimals = static::randomDigit();
        }

        if ($max === null) {
            $max = static::randomUnsignedNumber();
            if ($min > $max) {
                $max = $min;
            }
        }

        if ($min > $max) {
            $tmp = $min;
            $min = $max;
            $max = $tmp;
        }

        return round($min + mt_rand() / mt_getrandmax() * ($max - $min), $nbMaxDecimals);
    }

    /**
     * Returns a random integer with 0 to $nbDigits digits.
     *
     * The maximum value returned is mt_getrandmax()
     *
     * @param integer $nbDigits Defaults to a random number between 1 and 9
     * @param boolean $strict Whether the returned number should have exactly $nbDigits
     * @example 79907610
     *
     */
    public static function randomUnsignedNumber(?int $nbDigits = null, bool $strict = false) : int
    {
        if ($nbDigits === null) {
            $nbDigits = static::randomDigitNotNull();
        }
        $max = pow(10, $nbDigits) - 1;
        if ($max > mt_getrandmax()) {
            throw new InvalidArgumentException(
                'randomUnsignedNumber() can only generate numbers up to mt_getrandmax()',
            );
        }
        if ($strict) {
            return mt_rand(pow(10, $nbDigits - 1), $max);
        }

        return mt_rand(0, $max);
    }

    public static function randomNumber(?int $nrDigits = null, bool $strict = false) : int
    {
        $randomNumber = self::randomUnsignedNumber($nrDigits, $strict);
        return self::randomBoolean() ? $randomNumber : 0 - $randomNumber;
    }

    public static function randomDigit() : int
    {
        return mt_rand(0, 9);
    }

    public static function randomDigitNotNull() : int
    {
        return mt_rand(1, 9);
    }

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

    /**
     * @param string<string> ...$values
     */
    public static function chooseRandomString(string ... $values) : string
    {
        return $values[rand(0, count($values) -1)];
    }
}
