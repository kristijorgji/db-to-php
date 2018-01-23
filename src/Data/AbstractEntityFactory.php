<?php

namespace kristijorgji\DbToPhp\Data;

use kristijorgji\DbToPhp\Data\Exceptions\InvalidEntityFactoryFieldException;

abstract class AbstractEntityFactory
{
    /**
     * @var array
     */
    protected static $fields = [];

    /**
     * @param array $data
     * @throws InvalidEntityFactoryFieldException
     */
    public static function validateData(array $data)
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, static::$fields)) {
                throw new InvalidEntityFactoryFieldException(
                    sprintf(
                        'The given key: %s in the data array is not a valid key.%sAvailable keys are: (%s)',
                        $key,
                        PHP_EOL,
                        implode(', ', static::$fields)
                    )
                );
            }
        }
    }

    /**
     * @param array $data
     * @param $toClass
     * @return mixed
     */
    public static function mapArrayToEntity(array $data, $toClass)
    {
        $item  = new $toClass();

        foreach ($data as $key => $value) {
            $item->{'set' . snakeToPascalCase($key)}($value);
        }

        return $item;
    }

    /**
     * @return array
     */
    public static function randomArray() : array
    {
        return [
            self::randomString() => self::randomInt32()
        ];
    }

    /**
     * @return string
     */
    public static function randomJson() : string
    {
        return json_encode(self::randomArray());
    }

    /**
     * @param int $chanceOfGettingTrue
     * @return bool
     */
    public static function randomBoolean(int $chanceOfGettingTrue = 50) : bool
    {
        return mt_rand(1, 100) <= $chanceOfGettingTrue;
    }

    /**
     * @param string $format
     * @return string
     */
    public static function randomDate(string $format = 'Y-m-d H:i:s') : string
    {
        $now = time();
        return date($format, $now - self::randomUnsignedNumber(strlen($now) - 1));
    }

    /**
     * @return int
     */
    public static function randomInt8() : int
    {
        return rand(-128, 127);
    }

    /**
     * @return int
     */
    public static function randomUnsignedInt8() : int
    {
        return rand(0, 255);
    }

    /**
     * @return int
     */
    public static function randomInt16() : int
    {
        return rand(-32768, 32767);
    }

    /**
     * @return int
     */
    public static function randomUnsignedInt16() : int
    {
        return rand(0, 65535);
    }

    /**
     * @return int
     */
    public static function randomInt24() : int
    {
        return rand(-8388608, 8388607);
    }

    /**
     * @return int
     */
    public static function randomUnsignedInt24() : int
    {
        return rand(0, 16777215);
    }

    /**
     * @return int
     */
    public static function randomInt32() : int
    {
        return rand(-2147483648, 2147483647);
    }

    /**
     * @return int
     */
    public static function randomUnsignedInt32() : int
    {
        return rand(0, 4294967295);
    }

    /**
     * @return int
     */
    public static function randomInt64() : int
    {
        return rand(-9223372036854775808, 9223372036854775807);
    }

    /**
     * PHP max supported int is signed 64 bit integer
     * that's why in this case I return again an unsigned 32 bit int
     * which still is also a unsigned 64 bit int
     *
     * @return int
     */
    public static function randomUnsignedInt64() : int
    {
        return self::randomUnsignedInt32();
    }

    /**
     * @param int $digits
     * @return int
     */
    public static function randomYear(int $digits) : int
    {
        return self::randomUnsignedNumber($digits, true);
    }

    /**
     * Return a random float number
     *
     * @param int       $nbMaxDecimals
     * @param int|float $min
     * @param int|float $max
     * @example 48.8932
     *
     * @return float
     */
    public static function randomFloat($nbMaxDecimals = null, $min = 0, $max = null) : float
    {
        if (null === $nbMaxDecimals) {
            $nbMaxDecimals = static::randomDigit();
        }

        if (null === $max) {
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
     * @param boolean $strict   Whether the returned number should have exactly $nbDigits
     * @example 79907610
     *
     * @return integer
     */
    public static function randomUnsignedNumber(?int $nbDigits = null, bool $strict = false) : int
    {
        if (null === $nbDigits) {
            $nbDigits = static::randomDigitNotNull();
        }
        $max = pow(10, $nbDigits) - 1;
        if ($max > mt_getrandmax()) {
            throw new \InvalidArgumentException('randomUnsignedNumber() can only generate numbers up to mt_getrandmax()');
        }
        if ($strict) {
            return mt_rand(pow(10, $nbDigits - 1), $max);
        }

        return mt_rand(0, $max);
    }

    /**
     * @param int|null|null $nrDigits
     * @param bool $strict
     * @return int
     */
    public static function randomNumber(?int $nrDigits = null, bool $strict = false) : int
    {
        $randomNumber = self::randomUnsignedNumber($nrDigits, $strict);
        return self::randomBoolean() ? $randomNumber : (0 - $randomNumber);
    }

    /**
     * @return integer
     */
    public static function randomDigit() : int
    {
        return mt_rand(0, 9);
    }

    /**
     * @return integer
     */
    public static function randomDigitNotNull() : int
    {
        return mt_rand(1, 9);
    }

    /**
     * @param int $length
     * @return string
     */
    public static function randomString(int $length = 16)
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
     * @param string[] ...$values
     * @return string
     */
    public static function chooseRandomString(string... $values) : string
    {
        return $values[rand(0, count($values) -1)];
    }
}
