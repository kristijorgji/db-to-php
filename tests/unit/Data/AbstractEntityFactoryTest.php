<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Data;

use DateTime;
use InvalidArgumentException;
use kristijorgji\DbToPhp\Data\AbstractEntityFactory;
use kristijorgji\DbToPhp\Data\Exceptions\InvalidEntityFactoryFieldException;
use kristijorgji\Tests\Helpers\TestCase;
use function array_map;
use function in_array;
use function rand;
use function range;
use function strlen;

class AbstractEntityFactoryTest extends TestCase
{
    public function testValidateFields(): void
    {
        Test2EntityFactory::validateData([
            'id' => 'dummy',
            'name' => 'dummy',
            'surname' => 'dummy',
            'isWorking' => 'dummy',
            'salary' => 'dummy',
            'discount' => 'dummy',
            'new_column' => 'dummy',
            'dddd' => 'dummy',
            'binaeraylk' => 'dummy',
            'f' => 'dummy',
        ]);
        self::expectNotToPerformAssertions();
    }

    public function testValidateFields_invalid_field(): void
    {
        $this->expectException(InvalidEntityFactoryFieldException::class);
        Test2EntityFactory::validateData(['kari' => 2]);
    }

    public function testMapArrayToEntity(): void
    {
        $data = Test2EntityFactory::makeData();
        $actual = AbstractEntityFactory::mapArrayToEntity($data, Test2Entity::class);
        $this->assertInstanceOf(Test2Entity::class, $actual);
    }

    public function testRandomArray(): void
    {
        $actual = AbstractEntityFactory::randomArray();
        self::assertIsArray($actual);
    }

    public function testRandomJson(): void
    {
        $actual = AbstractEntityFactory::randomJson();
        $this->assertJson($actual);
    }

    public function testRandomBoolean(): void
    {
        $actual = AbstractEntityFactory::randomBoolean();
        self::assertIsBool($actual);
    }

    public function testRandomDate(): void
    {
        $format = 'Y-m-d H:i:s';

        for ($i = 0; $i < 177; $i++) {
            $actual = AbstractEntityFactory::randomDate($format);
            $d = DateTime::createFromFormat($format, $actual);
            $this->assertTrue($d && $d->format($format) === $actual);
        }
    }

    public function testRandomInt8(): void
    {
        for ($i = 0; $i < 27; $i++) {
            $actual = AbstractEntityFactory::randomInt8();
            $this->assertTrue($actual >= -128 && $actual <= 127);
        }
    }

    public function testRandomUnsignedInt8(): void
    {
        for ($i = 0; $i < 27; $i++) {
            $actual = AbstractEntityFactory::randomUnsignedInt8();
            $this->assertTrue($actual >= 0 && $actual <= 255);
        }
    }

    public function testRandomInt16(): void
    {
        for ($i = 0; $i < 27; $i++) {
            $actual = AbstractEntityFactory::randomInt16();
            $this->assertTrue($actual >= -32768 && $actual <= 32767);
        }
    }

    public function testRandomUnsignedInt16(): void
    {
        for ($i = 0; $i < 27; $i++) {
            $actual = AbstractEntityFactory::randomUnsignedInt16();
            $this->assertTrue($actual >= 0 && $actual <= 65535);
        }
    }

    public function testRandomInt24(): void
    {
        for ($i = 0; $i < 27; $i++) {
            $actual = AbstractEntityFactory::randomInt24();
            $this->assertTrue($actual >= -8388608 && $actual <= 8388607);
        }
    }

    public function testRandomUnsignedInt24(): void
    {
        for ($i = 0; $i < 27; $i++) {
            $actual = AbstractEntityFactory::randomUnsignedInt24();
            $this->assertTrue($actual >= 0 && $actual <= 16777215);
        }
    }

    public function testRandomInt32(): void
    {
        for ($i = 0; $i < 27; $i++) {
            $actual = AbstractEntityFactory::randomInt32();
            $this->assertTrue($actual >= -2147483648 && $actual <= 2147483647);
        }
    }

    public function testRandomUnsignedInt32(): void
    {
        for ($i = 0; $i < 27; $i++) {
            $actual = AbstractEntityFactory::randomUnsignedInt32();
            $this->assertTrue($actual >= 0 && $actual <= 4294967295);
        }
    }

    public function testRandomInt64(): void
    {
        for ($i = 0; $i < 27; $i++) {
            $actual = AbstractEntityFactory::randomInt64();
            $this->assertTrue($actual >= -9223372036854775808 && $actual <= 9223372036854775807);
        }
    }

    public function testRandomUnsignedInt64(): void
    {
        for ($i = 0; $i < 27; $i++) {
            $actual = AbstractEntityFactory::randomUnsignedInt64();
            $this->assertTrue($actual >= 0 && $actual <= 18446744073709551615);
        }
    }

    public function testRandomYear(): void
    {
        for ($i = 0; $i < 27; $i++) {
            $actual = AbstractEntityFactory::randomYear(4);
            $this->assertTrue($actual > 0 && strlen((string) $actual) === 4);
        }
    }

    public function testRandomFloat(): void
    {
        for ($i = 0; $i < 27; $i++) {
            $nrDecimals = rand(2, 5);
            $min = rand(1, 100);
            $max = rand(0, 1) ? rand(1, 100) : null;
            $actual = AbstractEntityFactory::randomFloat($nrDecimals, $min, $max);
            self::assertIsFloat($actual);
        }
    }

    public function testRandomFloat_min_greater_then_max(): void
    {
        $actual = AbstractEntityFactory::randomFloat(rand(1, 4), 21, 7);
        self::assertIsFloat($actual);
    }

    public function testRandomUnsignedNumber(): void
    {
        for ($i = 0; $i < 27; $i++) {
            $actual = AbstractEntityFactory::randomUnsignedNumber();
            $this->assertTrue($actual >= 0, $actual . ' should not be negative');
        }
    }

    public function testRandomUnsignedNumber_fixed_digits_number(): void
    {
        $nrDigits = rand(3, 7);
        $actual = AbstractEntityFactory::randomUnsignedNumber($nrDigits, true);
        $this->assertEquals($nrDigits, strlen((string) $actual));
        $this->assertTrue($actual > 0);
    }

    public function testRandomUnsignedNumber_overflow(): void
    {
        $this->expectException(InvalidArgumentException::class);
        AbstractEntityFactory::randomUnsignedNumber(100000000000000);
    }

    public function testRandomNumber(): void
    {
        $foundNegative = false;
        $foundPositive = false;

        while (!$foundNegative || !$foundPositive) {
            $actual = AbstractEntityFactory::randomNumber();
            if (!$foundNegative) {
                $foundNegative = $actual < 0;
            }
            if (!$foundPositive) {
                $foundPositive = $actual >= 0;
            }
        }

        $this->assertTrue($foundNegative && $foundPositive);
    }

    public function testChooseRandomString(): void
    {
        $values = array_map(function () {
            return self::randomString(rand(1, 7));
        }, range(0, rand(10, 21)));

        $chosen = AbstractEntityFactory::chooseRandomString(...$values);

        $this->assertTrue(in_array($chosen, $values));
    }

    public function testRandomString(): void
    {
        $randomString = AbstractEntityFactory::randomString(0);
        $this->assertEquals('', $randomString);
    }
}
