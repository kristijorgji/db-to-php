<?php

class BaseEntityFactory
{
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
     * @param int $chanceOfGettingTrue
     * @return bool
     */
    public static function randomBoolean(int $chanceOfGettingTrue = 50) : bool
    {
        return mt_rand(1, 100) <= $chanceOfGettingTrue;
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
     * @return int
     */
    public static function randomUnsignedInt64() : int
    {
        return rand(0, 18446744073709551615);
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
            $max = static::randomNumber();
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
    public static function randomNumber($nbDigits = null, $strict = false) : int
    {
        if (!is_bool($strict)) {
            throw new \InvalidArgumentException('randomNumber() generates numbers of fixed width. To generate numbers between two boundaries, use numberBetween() instead.');
        }
        if (null === $nbDigits) {
            $nbDigits = static::randomDigitNotNull();
        }
        $max = pow(10, $nbDigits) - 1;
        if ($max > mt_getrandmax()) {
            throw new \InvalidArgumentException('randomNumber() can only generate numbers up to mt_getrandmax()');
        }
        if ($strict) {
            return mt_rand(pow(10, $nbDigits - 1), $max);
        }

        return mt_rand(0, $max);
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
}
