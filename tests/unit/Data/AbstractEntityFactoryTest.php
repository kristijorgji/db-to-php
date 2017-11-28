<?php

namespace kristijorgji\UnitTests\Data;

use kristijorgji\DbToPhp\Data\AbstractEntityFactory;
use kristijorgji\Tests\Helpers\TestCase;

class AbstractEntityFactoryTest extends TestCase
{
    public function testMapArrayToEntity()
    {
        $data = Test2EntityFactory::makeData();
        $actual = AbstractEntityFactory::mapArrayToEntity($data, Test2Entity::class);
        $this->assertInstanceOf(Test2Entity::class, $actual);
    }

    public function testRandomArray()
    {
        $actual = AbstractEntityFactory::randomArray();
        $this->assertInternalType('array', $actual);
    }

    public function testRandomBoolean()
    {
        $actual = AbstractEntityFactory::randomBoolean();
        $this->assertInternalType('bool', $actual);
    }

    public function testRandomInt8()
    {
        for ($i = 0; $i < 177; $i++) {
            $actual = AbstractEntityFactory::randomInt8();
            $this->assertTrue($actual >= -128 && $actual <= 127);
        }
    }

    public function testRandomUnsignedInt8()
    {
        for ($i = 0; $i < 177; $i++) {
            $actual = AbstractEntityFactory::randomUnsignedInt8();
            $this->assertTrue($actual >= 0 && $actual <= 255);
        }
    }

    public function testRandomInt16()
    {
        for ($i = 0; $i < 177; $i++) {
            $actual = AbstractEntityFactory::randomInt16();
            $this->assertTrue($actual >= -32768 && $actual <= 32767);
        }
    }

    public function testRandomUnsignedInt16()
    {
        for ($i = 0; $i < 177; $i++) {
            $actual = AbstractEntityFactory::randomUnsignedInt16();
            $this->assertTrue($actual >= 0 && $actual <= 65535);
        }
    }

    public function testRandomInt24()
    {
        for ($i = 0; $i < 177; $i++) {
            $actual = AbstractEntityFactory::randomInt24();
            $this->assertTrue($actual >= -8388608 && $actual <= 8388607);
        }
    }

    public function testRandomUnsignedInt24()
    {
        for ($i = 0; $i < 177; $i++) {
            $actual = AbstractEntityFactory::randomUnsignedInt24();
            $this->assertTrue($actual >= 0 && $actual <= 16777215);
        }
    }

    public function testRandomInt32()
    {
        for ($i = 0; $i < 177; $i++) {
            $actual = AbstractEntityFactory::randomInt32();
            $this->assertTrue($actual >= -2147483648 && $actual <= 2147483647);
        }
    }

    public function testRandomUnsignedInt32()
    {
        for ($i = 0; $i < 177; $i++) {
            $actual = AbstractEntityFactory::randomUnsignedInt32();
            $this->assertTrue($actual >= 0 && $actual <= 4294967295);
        }
    }

    public function testRandomInt64()
    {
        for ($i = 0; $i < 177; $i++) {
            $actual = AbstractEntityFactory::randomInt64();
            $this->assertTrue($actual >= -9223372036854775808 && $actual <= 9223372036854775807);
        }
    }

    public function testRandomUnsignedInt64()
    {
        for ($i = 0; $i < 177; $i++) {
            $actual = AbstractEntityFactory::randomUnsignedInt64();
            $this->assertTrue($actual >= 0 && $actual <= 18446744073709551615);
        }
    }

    public function testRandomFloat()
    {
        for ($i = 0; $i < 177; $i++) {
            $nrDecimals = rand(2, 5);
            $min = rand(1, 100);
            $max = rand(0, 1) ? rand(1, 100) : null;
            $actual = AbstractEntityFactory::randomFloat($nrDecimals, $min, $max);
            $this->assertInternalType('float', $actual);
        }
    }

    public function testRandomFloat_min_greater_then_max()
    {
        $actual = AbstractEntityFactory::randomFloat(rand(1, 4), 21, 7);
        $this->assertInternalType('float', $actual);
    }

    public function testRandomNumber_fixed_digits_number()
    {
        $nrDigits = rand(3, 7);
        $actual = AbstractEntityFactory::randomNumber($nrDigits, true);
        $this->assertEquals($nrDigits, strlen((string) $actual));
    }

    public function testRandomNumber_overflow()
    {
        $this->expectException(\InvalidArgumentException::class);
        AbstractEntityFactory::randomNumber(100000000000000);
    }

    public function testChooseRandomString()
    {
        $values = array_map(function () {
            return self::randomString(rand(1, 7));
        }, range(0, rand(10, 21)));

        $chosen = AbstractEntityFactory::chooseRandomString(...$values);

        $this->assertTrue(in_array($chosen, $values));
    }

    public function testRandomString()
    {
        $randomString = AbstractEntityFactory::randomString(0);
        $this->assertEquals('', $randomString);
    }

    public function testRandomDigitNotNull()
    {

    }
}
